<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\TypeReclamationRepository;
use App\Entity\TypeReclamation;
use App\Form\TypeReclamationType;


#[Route('/TypeReclamation')]
class TypeReclamationController extends AbstractController
{
    #[Route('/', name: 'app_Typereclamation_index', methods: ['GET'])]
    public function index(TypeReclamationRepository $typereclamationRepository): Response
    { $result = $typereclamationRepository ->findAll();
        return $this -> render('/Type_reclamation/afficherType.html.twig',[
            'reclamations' => $result
        ]);
    }

    #[Route('/fetch', name: 'fetch')]
    public function fetchf(TypeReclamationRepository $typereclamationRepository): Response
    {
        $result = $typereclamationRepository ->findAll();
        return $this -> render('/Type_reclamation/afficherType.html.twig',[
            'reclamations' => $result
        ]);
    }

    #[Route('/newTypeReclamation', name: 'app_reclamation_newType', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new TypeReclamation();
        $form = $this->createForm(TypeReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('fetch', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Type_reclamation/Typeform.html.twig', [
            'reclamation' => $reclamation,
            'f' => $form,
        ]);
    }
    #[Route('/editType/{id}', name: 'app_typereclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, $id, TypeReclamationRepository $typereclamationRepository ): Response
    {
        $reclamation = $typereclamationRepository -> find($id);
        $form = $this->createForm(TypeReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('fetch', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Type_reclamation/Typeform.html.twig', [
            'reclamation' => $reclamation,
            'f' => $form,
        ]);
    }
    #[Route('/deleteType/{id}', name: 'app_typereclamation_delete')]
    public function deletef(TypeReclamationRepository $reclamationRepository , $id, ManagerRegistry $mr): Response
    {
        $dn=$reclamationRepository->find($id);
        $em=$mr->getManager();
        $em->remove($dn);
        $em->flush(); 

        return $this->redirectToRoute('fetch');
    }
}
