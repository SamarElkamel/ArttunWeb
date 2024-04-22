<?php

namespace App\Controller;

use App\Entity\user\User;
use App\Form\UserType1;
use App\Repository\user\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginSignupController extends AbstractController
{

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /*public function index(EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils,UserRepository $userrepo,Request $request)
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
    }*/
#[Route('/signup', name: 'app_signup')]
public function signup(PasswordHasherInterface $asher,ManagerRegistry $repository ,Request $request):Response{
    $user = new User();
    $em=$repository->getManager();
    $form = $this->createForm(UserType1::class,$user);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid() ){
            $user->setAdresse(52);
        $user->setPhoto("52");
        $user->setMdp($asher->hash($user->getMdp()));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("app_user_crud");
    }
    return $this->render('login_signup/signup.html.twig',['form'=>$form->createView()]);
}
}
