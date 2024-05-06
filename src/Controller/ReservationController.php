<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\user\User;
use App\Repository\EvenementRepository;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager , Security $security): Response
    {
        $user = $security->getUser();
        $userId = $user->getId();
        $reservations = $this->getDoctrine()->getRepository(Reservation::class)->findBy(['idClient' => $userId]);

    return $this->render('reservation/index.html.twig', [
        'reservations' => $reservations,
    ]);
    }

  
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , EvenementRepository $evenementRepository ,Security $security  ): Response
    {
       
        $evenementId = $request->query->get('id'); 
        
        $evenement = $evenementRepository->find($evenementId);
        
       // $eventId = $request->request->get('eventId');
        $reservation = new Reservation();
      //  $event = $evenementRepository->find($eventId );
      
      if ($request->isMethod('POST')) {
        $user = $security->getUser();
       // $user = $entityManager->getRepository(User::class)->find(44);
       
        $numberOfGuests = $request->request->get('guests');
        $totalPrice = $request->request->get('totalPrice');
       
        $reservation->setIdClient($user); 
        $reservation->setIdEvenement($evenement);
        $reservation->setNbinvite($numberOfGuests ?? 0);
        $reservation->setTotalPrix($totalPrice ?? 0);
        $reservation->setDate(new \DateTime());

       

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);

        $entityManager->flush();

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        

        }
        

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            '_venement' => $evenement,
            
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

   



}
