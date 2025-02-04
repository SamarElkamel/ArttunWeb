<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app__venement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('Evenement/index.html.twig', [
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
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $uploadsDirectory = $this->getParameter('uploads_directory');
                $photoFileName = md5(uniqid()) . '.' . $photoFile->guessExtension();
                $photoFile->move($uploadsDirectory, $photoFileName);
                $Evenement->setPhoto($photoFileName);
            }
            else 
            $Evenement->setPhoto('https://live.staticflickr.com/7481/15343881233_eedf62af28_z.jpg');

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
