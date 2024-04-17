<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\user\User;
use App\Entity\Command;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route("/add-to-panier/{ref}", name: "add_to_panier")]
public function addToPanier($ref, EntityManagerInterface $entityManager): Response
{
    
    $id = 1;

    $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['ref' => $ref]);

    $user = $this->getDoctrine()->getRepository(User::class)->find($id);

    if (!$produit || !$user) {
        throw $this->createNotFoundException('Produit ou utilisateur non trouvé');
    }

    $panier = new Panier();
    $panier->setRefProduit($produit);
    $panier->setIdClient($user);

    $entityManager->persist($panier);
    $entityManager->flush();
    return $this->redirectToRoute('app_produit_index');
}

#[Route("/checkout", name: "checkout")]
public function checkout(Request $request, EntityManagerInterface $entityManager): Response
{
  
    $paniers = $this->getDoctrine()->getRepository(Panier::class)->findAll();

    $reference = 'CDAT' . random_int(1000, 9999);

   
    $date = new \DateTime();

    
    $totalPrice = 0;
    $commande = new Command();
    $commande->setReference($reference);
    $commande->setDate($date);
    
   
    $idClient = 1; 
    $user = $this->getDoctrine()->getRepository(User::class)->find($idClient);
    if (!$user) {
    }
   
    $commande->setIdClient($user);
    
   
   

   
    foreach ($paniers as $panier) {
        $totalPrice += $panier->getRefProduit()->getPrix();
        
     
       // $commande->setIdProduit($panier->getRefProduit());
    }

    
    $commande->setPrix($totalPrice);
    
   
    $entityManager->persist($commande);
    $entityManager->flush();

  
    foreach ($paniers as $panier) {
        $entityManager->remove($panier);
    }
    $entityManager->flush();
    return $this->redirectToRoute('app_panier_index');
}



    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        // Fetch the paniers
        $paniers = $panierRepository->findAll();
    
        // Initialize total price variable
        $totalPrice = 0;
        $NB_items = 0;
    
        // Calculate total price
        foreach ($paniers as $panier) {
            $NB_items ++;
            $totalPrice += $panier->getRefProduit()->getPrix();
        }
    
        // Pass the total price to the Twig template
        return $this->render('panier/index.html.twig', [
            'nbitems'=> $NB_items,
            'paniers' => $paniers,
            'totalPrice' => $totalPrice,
        ]);
    }

    /*#[Route('/checkout', name: 'checkout_process', methods: ['POST'])]
    public function checkoutProcess(Request $request): Response
    {
        // Retrieve data from the form
        $totalPrice = $request->request->get('totalPrice');
        $nbItems = $request->request->get('nbItems');
    
        // Generate reference for CommandesProduits
        $reference = 'CDAT' . random_int(1000, 9999);
    
        // Create and persist a new CommandesProduits entity
        $commandeProduits = new CommandesProduits();
        $commandeProduits->setReference($reference);
        $commandeProduits->setQuantite($nbItems);
        $commandeProduits->setPrixtotal($totalPrice);
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commandeProduits);
        $entityManager->flush();
    
        // Return a response
        return new Response('Checkout process completed successfully!');
        return $this->render('panier/index.html.twig', [
            
        ]);
    }*/
    
    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    }
}
