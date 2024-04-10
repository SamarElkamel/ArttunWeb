<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserCrudController extends AbstractController
{
    #[Route('/back/user/crud', name: 'app_user_crud')]
    public function index(EntityManagerInterface $entityManager,UserRepository $userrepo,Request $request): Response
    {
        $user = new User();

        $users = $userrepo->findAll();
        $userCount = $userrepo->count([]);
        return $this->render('user_crud/index.html.twig', [
            'users' => $users,
            'count' => $userCount,
            'controller_name' => 'UserCrudController',
        ]);
    }
    #[Route('/back/user/crud/edit', name: 'app_user_crud_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request){
        echo "ID: " . $request->request->get('passwordfield') . "<br>";
        $id = $request->request->get('idfield');
        $nom = $request->request->get('nomfield');
        $prenom = $request->request->get('prenomfield');
        $mail = $request->request->get('mailfield');
        $password = $request->request->get('passwordfield');
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Update user properties
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setAdresseMail($mail);
        $user->setMdp($password);
        // Set other properties...

        // Persist changes to the database
        $entityManager->flush();

        // Optionally, redirect to a success page or return a response
        return $this->redirectToRoute('app_user_crud');
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
