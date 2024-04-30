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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route("/add-to-panier/{ref}", name: "add_to_panier")]
    public function addToPanier($ref, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        
            $id = 1;
    
            $produit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['ref' => $ref]);
        
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        
            if (!$produit || !$user) {
                throw $this->createNotFoundException('Produit ou utilisateur non trouvé');
            }
    
            // Check if the product is already in the cart
            $existingPanier = $entityManager->getRepository(Panier::class)->findOneBy([
                'ref_produit' => $produit,
                'id_client' => $user
            ]);
            
            if ($existingPanier) {
                $session->getFlashBag()->add('error', 'Ce produit est déjà ajouté au panier.');
                return $this->redirectToRoute('app_produit_index');
            }
        
            $panier = new Panier();
            $panier->setRefProduit($produit);
            $panier->setIdClient($user);
        
            $entityManager->persist($panier);
            $entityManager->flush();
            
            
            // Redirect with a flash message if successful
            $session->getFlashBag()->add('success', 'Produit ajouté au panier avec succès.');
            
            return $this->redirectToRoute('app_produit_index');
       
    }
    

    #[Route("/checkout", name: "checkout")]
    public function checkout(Request $request, EntityManagerInterface $entityManager, $stripeSK): Response
    {
        $paniers = $this->getDoctrine()->getRepository(Panier::class)->findAll();
        $id = 1; // Assuming the client ID for now
    
        // Set up initial variables
        $reference = 'CDAT' . random_int(1000, 9999);
        $date = new \DateTime();
        $totalPrice = 0;
        $productCount = 0;
        $idclient = $this->getDoctrine()->getRepository(User::class)->find($id);
    
        // Iterate over the paniers to calculate total price and product count
        foreach ($paniers as $panier) {
            $totalPrice += $panier->getRefProduit()->getPrix();
            $productCount++;
        }
    
        // If the client has ordered more than 2 products
        if ($productCount > 1) {
            // Insert each product into a separate command with the same reference
            foreach ($paniers as $panier) {
                $commande = new Command();
                $commande->setReference($reference);
                $commande->setDate($date);
                $commande->setIdClient($idclient);
                $commande->setIdProduit($panier->getRefProduit());
                $commande->setPrix($panier->getRefProduit()->getPrix());
                $entityManager->persist($commande);
            }
        } else {
            // Insert all products into a single command
            $commande = new Command();
            $commande->setReference($reference);
            $commande->setDate($date);
            $commande->setIdClient($idclient);
            foreach ($paniers as $panier) {
                $commande->setIdProduit($panier->getRefProduit());
                $commande->setPrix($totalPrice);
            }
            $entityManager->persist($commande);
        }
    
        // Flush changes to the database
        $entityManager->flush();
    
        // Clear the cart after checkout
        foreach ($paniers as $panier) {
            $entityManager->remove($panier);
        }
        $entityManager->flush();
    
        // Set up Stripe
        Stripe::setApiKey($stripeSK);
    
        // Create Stripe session
        $sessionData = [
            'payment_method_types' => ['card'],
            'line_items'           => [],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ];
    
        foreach ($paniers as $panier) {
            // Inserting product data dynamically
            $productData = [
                'name' => $panier->getRefProduit()->getNom(), // Assuming 'Nom' is the property storing product names
            ];
    
            // Inserting price data dynamically based on the number of items and your algorithm
            $unitPrice = $panier->getRefProduit()->getPrix(); // Assuming 'Prix' is the property storing product prices
            $quantity = $productCount / count($paniers); // Adjusted this line
 
            $lineItem = [
                'price_data' => [
                    'currency'     => 'usd', // Assuming USD currency
                    'product_data' => $productData,
                    'unit_amount'  => $unitPrice * 100, // Stripe requires amount in cents
                ],
                'quantity'   => $quantity,
            ];
    
            $sessionData['line_items'][] = $lineItem;
        }
    
        $session = Session::create($sessionData);
    
        // Redirect user to Stripe checkout page
        return $this->redirect($session->url, 303);
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
    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('panier/success.html.twig', []);
    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('panier/cancel.html.twig', []);
    }
}
