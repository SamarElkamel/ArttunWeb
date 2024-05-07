<?php

namespace App\Controller;

use App\Service\BadWordFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use App\Entity\TypeReclamation;
use App\Repository\TypeReclamationRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Form;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



#[Route('/reclamation')]
class ReclamationController extends AbstractController
{ 
    
    private  $idClient =5;
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository, BadWordFilter $badWordFilter): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
            'badWordFilter' => $badWordFilter
        ]);
    }

    #hedhi thez ll front
    #[Route('/front', name: 'app_reclamation_front')]
    public function indexf(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('baseFront.html.twig', [
            'reclamations' => $reclamationRepository->findAll()
           
        ]);
    }
    #list front




#[Route('/login', name: 'login')]
public function login(ReclamationRepository $reclamationRepository): Response
{
    return $this->render('/reclamation/login.html.twig', [
        'reclamations' => $reclamationRepository->findAll()
       
    ]);
}

    

    #[Route('/fetchf', name: 'fetchf')]
public function fetchf(ReclamationRepository $reclamationRepository, BadWordFilter $badWordFilter): Response
{
    // Fetch reclamations where idClient is equal to 5 (or any other desired id)
    // You can change this value to any other id
    $idClient = $this->idClient;
    $reclamations = $reclamationRepository->findBy(['idClient' => $idClient]);
    
    return $this->render('/reclamation/afficherRec.html.twig', [
        'reclamations' => $reclamations,
        'badWordFilter' => $badWordFilter
    ]);
}


    
#[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, TypeReclamationRepository $typeReclamationRepository,MailerInterface $mailer
): Response
{


    if ($this->idClient === "") {
        // Redirect to login page
        return $this->redirectToRoute('login'); // Change 'login' to your login route name
    }
    // Fetch TypeReclamation entities
    $typeReclamations = $typeReclamationRepository->findAll();

    // Initialize the counter
    $counter = 1;
    
    // Map Libelle to incremental values
    $choices = [];
    foreach ($typeReclamations as $typeReclamation) {
        $choices[$typeReclamation->getLibelle()] = $counter;
        $counter++;
    }

    // Create a new Reclamation instance and set the idClient
    $idClient = $this->idClient;
    $reclamation = new Reclamation();
    $reclamation->setIdClient($idClient); // Set the idClient

    // Create the form with "titre", "description", and "idType" fields only
    $form = $this->createFormBuilder($reclamation)
        ->add('titre')
        ->add('description')
        ->add('idType', ChoiceType::class, [
            'choices' => $choices,
            'label' => 'Type de Réclamation',
            'attr' => ['class' => 'form-control'],
        ])
        ->getForm();
    
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // If the form is submitted and valid, persist the reclamation
        $entityManager->persist($reclamation);
        $entityManager->flush();
        $email = (new Email())
        ->from ('artun763@gmail.com')
        ->to('stalzrka@gmail.com')
        ->priority(Email::PRIORITY_HIGH) 
        ->subject('Reclamation')
    // If you want use text mail only
        ->text(' Reclamation a envoyer avec succes ') 
    ;try {
        $mailer->send($email);
        // If the email was sent successfully, return a success response
        return new Response('Email sent successfully!');
    } catch (\Exception $e) {
        // If there was an error, return an error response with the error message
        return new Response('Error sending email: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

        return $this->redirectToRoute('fetchf', [], Response::HTTP_SEE_OTHER);
    }

    // If the form is not submitted or not valid, render the form template
    return $this->renderForm('reclamation/_form.html.twig', [
        'reclamation' => $reclamation,
        'f' => $form,
    ]);
}

   



   #[Route('/edit/{id}', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, EntityManagerInterface $entityManager, $id, ReclamationRepository $reclamationRepository): Response
{
    $rec = $reclamationRepository->find($id);
    
    // Create a form with only the "reply" field
    $form = $this->createFormBuilder($rec)
        ->add('reply') // Only include the "reply" field
        ->getForm();
    
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($rec);
        $entityManager->flush();

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reclamation/reply.html.twig', [
        'reclamation' => $rec,
        'f' => $form,
    ]);
}


#[Route('/editf/{id}', name: 'app_reclamation_editf', methods: ['GET', 'POST'])]
public function editf(Request $request, EntityManagerInterface $entityManager, $id, ReclamationRepository $reclamationRepository, TypeReclamationRepository $typeReclamationRepository): Response
{
    $rec = $reclamationRepository->find($id);
    
    // Fetch TypeReclamation entities
    $typeReclamations = $typeReclamationRepository->findAll();

    // Initialize the counter
    $counter = 1;
    
    // Map Libelle to incremental values
    $choices = [];
    foreach ($typeReclamations as $typeReclamation) {
        $choices[$typeReclamation->getLibelle()] = $counter;
        $counter++;
    }

    // Create a form with "titre", "description", and "idType" fields only
    $form = $this->createFormBuilder($rec)
        ->add('titre')
        ->add('description')
        ->add('idType', ChoiceType::class, [
            'choices' => $choices,
            'label' => 'Type de Réclamation',
            'attr' => ['class' => 'form-control'],
        ])
        ->getForm();
    
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($rec);
        $entityManager->flush();

        return $this->redirectToRoute('fetchf', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reclamation/editfront.html.twig', [
        'reclamation' => $rec,
        'f' => $form,
    ]);
}



    #[Route('/delete/{id}', name: 'app_reclamation_delete')]
    public function delete(ReclamationRepository $reclamationRepository , $id, ManagerRegistry $mr): Response
    {
        $dn=$reclamationRepository->find($id);
        $em=$mr->getManager();
        $em->remove($dn);
        $em->flush(); 

        return $this->redirectToRoute('app_reclamation_index');
    }
    #[Route('/deletef/{id}', name: 'app_reclamation_deletef')]
    public function deletef(ReclamationRepository $reclamationRepository , $id, ManagerRegistry $mr): Response
    {
        $dn=$reclamationRepository->find($id);
        $em=$mr->getManager();
        $em->remove($dn);
        $em->flush(); 

        return $this->redirectToRoute('fetchf');
    }
}
