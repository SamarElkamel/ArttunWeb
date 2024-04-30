<?php

namespace App\Controller;

use App\Entity\user\User;
use App\Form\UserType1;
use App\Repository\user\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserCrudController extends AbstractController
{
    #[Route('/back/user/crud', name: 'app_user_crud')]
    public function index(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request/*,PasswordHasherInterface $encoder*/): Response
    {
        $users = $userRepository->findAll();
        $adminCount = 0;
        $clientCount = 0;
        $livreurCount = 0;
        foreach ($users as $user) {
            switch ($user->getType()) {
                case 'admin':
                    $adminCount++;
                    break;
                case 'client':
                    $clientCount++;
                    break;
                case 'livreur':
                    $livreurCount++;
                    break;
                // Add more cases if you have more roles
            }
        }
        $userCount = count($users);
        $user = new User();
        // Create the form and pass the User object to it
        $form = $this->createForm(UserType1::class, $user);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form['photo']->getData();
            if ($photo) {
                $fichier = md5(uniqid()) . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('upload_directory'), $fichier);
                $user->setPhoto($fichier);
            }
            //$user->setMdp($encoder->hash($user->getMdp()));

            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to prevent form resubmission
            return $this->redirectToRoute('app_user_crud');
        }

        return $this->render('user_crud/index.html.twig', [
            'adminCount' => $adminCount,
            'clientCount' => $clientCount,
            'livreurCount' => $livreurCount,
            'users' => $users,
            'count' => $userCount,
            'controller_name' => 'UserCrudController',
            'form' => $form->createView(),
        ]);
    }
    #[Route('/stat', name: 'app_stat_user')]
    public function stat(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request/*,PasswordHasherInterface $encoder*/): Response
    {
        $users = $userRepository->findAll();
        $adminCount = 0;
        $clientCount = 0;
        $livreurCount = 0;
        foreach ($users as $user) {
            switch ($user->getType()) {
                case 'admin':
                    $adminCount++;
                    break;
                case 'client':
                    $clientCount++;
                    break;
                case 'livreur':
                    $livreurCount++;
                    break;
                // Add more cases if you have more roles
            }
        }

        return $this->render('user_crud/stats.html.twig', [
            'adminCount' => $adminCount,
            'clientCount' => $clientCount,
            'livreurCount' => $livreurCount,
        ]);
    }
    #[Route('/back/user/crud/delete{id}', name: 'app_user_crud_delete')]
    public function delete(EntityManagerInterface $entityManager, Request $request,$id){
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        try {
            $entityManager->remove($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->redirectToRoute('app_user_crud');
    }
}
