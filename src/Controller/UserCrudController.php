<?php

namespace App\Controller;

use App\Entity\user\User;
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
        $fileName = $request->request->get('filefield');
        $type = $request->request->get('type');
        $user = $entityManager->getRepository(User::class)->find($id);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Check if the file is a picture
        $isPicture = false;
        if ($fileExtension !== '') {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $isPicture = in_array(strtolower($fileExtension), $allowedExtensions);
        }

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $file=
        // Update user properties
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setAdresseMail($mail);
        $user->setMdp($password);
        if($type="2")
        $user->setType("client");
        if($type="1")
            $user->setType("admin");
        $user->setPhoto($fileName);
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
