<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Entity\CategorieProduit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twilio\Rest\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Entity\Command;

use Pagerfanta\Pagerfanta;
use App\Entity\user\User;
use Pagerfanta\Doctrine\ORM\QueryAdapter as DoctrineORMAdapter;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;// Add this import if it's not already imported
use App\Repository\CategorieProduitRepository;  
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Repository\CommandRepository;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/index', name: 'app_produit_index', methods: ['GET'])]
    public function index(Request $request, ProduitRepository $produitRepository): Response
{
    // Récupérer tous les produits
    $queryBuilder = $produitRepository->createQueryBuilder('p')
        ->orderBy('p.ref', 'ASC');

    // Créer un adaptateur Pagerfanta pour Doctrine ORM
    $adapter = new DoctrineORMAdapter($queryBuilder);

    // Créer une instance de Pagerfanta
    $pagerfanta = new Pagerfanta($adapter);

    // Définir le nombre d'éléments par page
    $pagerfanta->setMaxPerPage(5); // Vous pouvez ajuster cette valeur selon vos besoins

    // Récupérer le numéro de page à partir de la requête
    $page = $request->query->getInt('page', 1);

    try {
        // Définir la page actuelle
        $pagerfanta->setCurrentPage($page);
    } catch (OutOfRangeCurrentPageException $e) {
        // Gérer une exception si la page demandée est hors de portée
        throw new NotFoundHttpException('Page not found', $e);
    }

    // Rendre la vue avec les produits paginés
    return $this->render('produit/index.html.twig', [
        'pagerfanta' => $pagerfanta,
    ]);
}


    
        #[Route('/index/stat', name: 'app_produit_stat', methods: ['GET'])]
        public function indexstat(ProduitRepository $produitRepository): Response
        {
            $produits = $produitRepository->findAll();

        // Calculer les statistiques par catégorie
        $statsByCategory = [];
        foreach ($produits as $produit) {
            $category = $produit->getCategorieProduit()->getLibelle();
            if (!isset($statsByCategory[$category])) {
                $statsByCategory[$category] = 1;
            } else {
                $statsByCategory[$category]++;
            }
        }
        
        // Trouver la valeur maximale des statistiques
        $maxCount = max($statsByCategory);

        return $this->render('produit/stat.html.twig', [
            'produits' => $produits,
            'statsByCategory' => $statsByCategory,
            'maxCount' => $maxCount,
            ]);
        }






    #[Route('/front', name: 'app_produit_indexfront', methods: ['GET'])]
    public function indexfront(CategorieProduitRepository $categorieProduitRepository,ProduitRepository $produitRepository,CommandRepository $commandRepository): Response
    {
       
      
        
            // Retrieve all products
            $produits = $produitRepository->findAll();
        
            // Retrieve the references of ordered products
            $referencesCommandees = $commandRepository->findReferencesOfOrderedProducts();
        
            // Filter the products to display only those that haven't been ordered
            $produitsNonCommandes = array_filter($produits, function($produit) use ($referencesCommandees) {
                // Check if the product reference exists in the ordered product references
                return !in_array($produit->getRef(), $referencesCommandees);
            });
        
            // Return the view with the filtered products
           
        
        return $this->render('produit/front.html.twig', [
            'produits' => $produitsNonCommandes,
            'categorie_produits' => $categorieProduitRepository->findAll(),

        ]);
    }
    
    #[Route('/index/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
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
        $accountSid = 'ACbc5608278bb28c4de0cde4b73f954e65';
        $authToken = 'a3baa74bec138d281804d2dabf8fd706';
        $twilioNumber = '+17079992405';
        
        // Initialize Twilio client
        $client = new Client($accountSid, $authToken);
        
        // Replace 'recipient_number' with the actual recipient's phone number
        $recipientNumber = '+21628482349';
        
        $messageBody = 'un nouveau produit est ajouté.';
        
        // Send the message
        $message = $client->messages->create(
            $recipientNumber,
            [
                'from' => $twilioNumber,
                'body' => $messageBody
            ]
        );
        
        echo 'Message SID: ' . $message->sid;
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

    #[Route('/index/{ref}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/index/{ref}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
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


#[Route('/index/export/pdf', name: 'app_produit_export_pdf', methods: ['GET', 'POST'])]
public function exportPdf(EntityManagerInterface $entityManager): Response
{
    // Initialize Dompdf
    $dompdf = new Dompdf();
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf->setOptions($options);

    // Fetch data
    $produits = $entityManager->getRepository(Produit::class)->findAll();

    // HTML content for PDF
    $html = $this->renderView('produit/pdf.html.twig', [
        'produits' => $produits,
    ]);

    // Load HTML content
    $dompdf->loadHtml($html);

    // Render PDF
    $dompdf->render();

    // Stream the file to the client
    return new Response(
        $dompdf->output(),
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="produits.pdf"',
        ]
    );
}



    #[Route('/index/{ref}/delete', name: 'app_produit_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getRef(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}








