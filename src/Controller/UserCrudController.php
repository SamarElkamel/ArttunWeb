<?php

namespace App\Controller;

use App\Entity\user\User;
use App\Form\UserType1;
use App\Repository\user\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserCrudController extends AbstractController
{
    #[Route('/back/user/crud', name: 'app_user_crud')]
    public function index(EntityManagerInterface $entityManager,UserRepository $userrepo,Request $request): Response
    {
        $userrepo = $entityManager->getRepository(User::class);
        $users = $userrepo->findAll();
        $userCount = $userrepo->count([]);

        // If there is an ID parameter in the request, fetch the user by ID
        $userId = $request->get('idfield');
        $user = $userId ? $userrepo->find($userId) : new User();

        // Create the form and pass the User object to it
        $form = $this->createForm(UserType1::class, $user);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Flush the changes to the database
            $entityManager->flush();

            // Redirect to prevent form resubmission
            return $this->redirectToRoute('app_user_crud');
        }

        return $this->render('user_crud/index.html.twig', [
            'users' => $users,
            'count' => $userCount,
            'controller_name' => 'UserCrudController',
            'form' => $form->createView(),
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
