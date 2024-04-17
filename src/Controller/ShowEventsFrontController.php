<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;

class ShowEventsFrontController extends AbstractController
{
    #[Route('/show/events/front', name: 'app_show_events_front')]
    public function index(EvenementRepository $evenementRepository): Response
    {


        return $this->render('front_office/Evenement/showEvent.html.twig', [
            '_venements' => $evenementRepository->findAll(),
        ]);
    }
}

