<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app__venement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository , EntityManagerInterface $entityManager): Response
    {


        $reservationCount = $entityManager->getRepository(Reservation::class)->createQueryBuilder('r')
        ->select('COUNT(r.id)')
        ->getQuery()
        ->getSingleScalarResult();

    $totalPrice = $entityManager->getRepository(Reservation::class)->createQueryBuilder('r')
        ->select('SUM(r.totalprix)')
        ->getQuery()
        ->getSingleScalarResult();

        $totalEvents = $entityManager->getRepository(Evenement::class)->createQueryBuilder('e')
        ->select('COUNT(e.id)')
        ->getQuery()
        ->getSingleScalarResult();

       $upcomingEvents = $entityManager->getRepository(Evenement::class)->createQueryBuilder('e')
        ->select('COUNT(e.id)')
        ->where('e.date > :currentDate')
        ->setParameter('currentDate', new \DateTime())
        ->getQuery()
        ->getSingleScalarResult();
        
        return $this->render('Evenement/index.html.twig', [
            'reservationCount' => $reservationCount,
            'totalPrice' => $totalPrice,
            'totalEvents' => $totalEvents,
            'upcomingEvents' => $upcomingEvents,
            '_venements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app__venement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $Evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $Evenement);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
    
            if ($photo) {
                $fichier = md5(uniqid()) . '.' . $photo->guessExtension();
                $photo->move($this->getParameter('upload_directory'), $fichier);
                $Evenement->setPhoto($fichier);
            }
    
            $entityManager->persist($Evenement);
            $entityManager->flush();
    
            return $this->redirectToRoute('app__venement_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('Evenement/new.html.twig', [
            '_venement' => $Evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app__venement_show', methods: ['GET'])]
    public function show(Evenement $Evenement): Response
    {
        return $this->render('Evenement/show.html.twig', [
            '_venement' => $Evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app__venement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $Evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $Evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app__venement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Evenement/edit.html.twig', [
            '_venement' => $Evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app__venement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $Evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$Evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($Evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app__venement_index', [], Response::HTTP_SEE_OTHER);
    }


  

}
