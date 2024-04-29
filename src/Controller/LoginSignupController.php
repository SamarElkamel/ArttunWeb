<?php

namespace App\Controller;

use App\Repository\user\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
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
#[Route('/signup', name: 'app_signup')]
public function signup(ManagerRegistry $repository ,Request $request):Response{
    $user = new User();
    $em=$repository->getManager();
    $form = $this->createForm(UserType1::class,$user);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid() ){
        $photo = $form['photo']->getData();
        if ($photo) {
            $fichier = md5(uniqid()) . '.' . $photo->guessExtension();
            $photo->move($this->getParameter('upload_directory'), $fichier);
            $user->setPhoto($fichier);
        }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("app_user_crud");
    }
}
