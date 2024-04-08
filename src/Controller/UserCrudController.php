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
}
