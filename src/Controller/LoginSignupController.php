<?php

namespace App\Controller;

use App\Repository\user\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginSignupController extends AbstractController
{

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    public function index(EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils,UserRepository $userrepo,Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            // Find user by email
            $user = $userrepo->findOneBy(['adresseMail' => $email]);
            if ($user->getMdp()==$password){
                $session=$request->getSession();
                $session->set("email",$user->getAdresseMail());
                $session->set("nom",$user->getNom());
                $session->set("prenom",$user->getPrenom());
            $this->session->set('imagepath',$user->getPhoto());
                return $this->redirectToRoute('app_login_signup');
            }
        }
    }
}
