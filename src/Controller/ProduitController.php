<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface; // Add this import if it's not already imported


#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }


    #[Route('/front', name: 'app_produit_indexfront', methods: ['GET'])]
    public function indexfront(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/front.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $pictureFile = $form['image']->getData();
        if ($pictureFile) {
            $filename = md5(uniqid()) . '.' . $pictureFile->guessExtension();
            $pictureFile->move(
                $this->getParameter('upload_directory'),
                $filename
            );
            $produit->setImage($filename);
        }

        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_produit_index');
    }

    return $this->render('produit/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    private function uploadFile(UploadedFile $file, SluggerInterface $slugger): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->getParameter('upload_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // Handle file upload error
            throw new \Exception('Error uploading file');
        }

        return $newFilename;
    }

    #[Route('/{ref}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/{ref}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $uploadedFile = $form->get('image')->getData();
        if ($uploadedFile instanceof UploadedFile) {
            $newFilename = $this->uploadFile($uploadedFile, $slugger);
            $produit->setImage($newFilename);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_produit_index');
    }

    return $this->render('produit/edit.html.twig', [
        'produit' => $produit,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{ref}/delete', name: 'app_produit_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getRef(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
