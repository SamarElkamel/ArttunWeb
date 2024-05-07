<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\Command;
use App\Controller\CommandController;
use App\Entity\Livreur;

use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Twilio\Rest\Client;

#[Route('/mission')]
class MissionController extends AbstractController
{
    #[Route('/', name: 'app_mission_index', methods: ['GET'])]
    public function index(Request $request, MissionRepository $missionRepository): Response
    {
        $etat = $request->query->get('etat');

        // Récupérer toutes les missions attribuées au livreur avec l'ID 75
        $filteredMissions = $missionRepository->findBy(['livreur' => 75]);

        // Si l'état est spécifié, filtrer les missions en fonction de l'état également
        if ($etat) {
            $filteredMissions = array_filter($filteredMissions, function($mission) use ($etat) {
                return $mission->getEtat() === $etat;
            });
        }

        return $this->render('mission/index.html.twig', [
            'missions' => $filteredMissions,
        ]);
    }

    #[Route('/{idMission}', name: 'app_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }
        #[Route('/livrer-mission/{idMission}', name: 'update_mission_state', methods: ['GET'])]

    public function livrerMission(Request $request, int $idMission)
    {
        $mission = $this->getDoctrine()->getRepository(Mission::class)->find($idMission);
    
        if (!$mission) {
            throw $this->createNotFoundException('Mission non trouvée avec l\'ID : '.$idMission);
        }
    
        // Mettre à jour l'état de la mission
        $mission->setEtat('livré');
    
        // Enregistrer les modifications dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($mission);
        $entityManager->flush();
// Integrate Twilio API to send confirmation message
// Your Twilio credentials
$accountSid = 'ACbc5608278bb28c4de0cde4b73f954e65';
$authToken = 'a3baa74bec138d281804d2dabf8fd706';
$twilioNumber = '+17079992405';

// Initialize Twilio client
$client = new Client($accountSid, $authToken);

// Replace 'recipient_number' with the actual recipient's phone number
$recipientNumber = '+21628482349';

// Get the mission ID
$missionId = $mission->getIdMission();

// Compose the message including the mission ID
$messageBody = 'Your delivery for mission ID: ' . $missionId . ' has been successfully confirmed.';

// Send the message
$message = $client->messages->create(
    $recipientNumber,
    [
        'from' => $twilioNumber,
        'body' => $messageBody
    ]
);

// Log the message SID to track the delivery status if needed
echo 'Message SID: ' . $message->sid;

    
        // Rediriger l'utilisateur vers la page précédente ou une autre page
        // Dans cet exemple, nous redirigeons simplement l'utilisateur vers la page d'accueil
        return $this->redirectToRoute('app_mission_index');
    }

    #[Route('/create-mission', name: 'create_mission', methods: ['POST'])]
    public function createMission(Request $request): Response
    {
        // Récupérer les identifiants des commandes sélectionnées depuis la requête
        $selectedCommandIds = $request->request->get('selected_commands');
    
        // Vérifier si $selectedCommandIds est un tableau valide et non vide
        if (is_array($selectedCommandIds) && !empty($selectedCommandIds)) {
            $entityManager = $this->getDoctrine()->getManager();
    
            // Créer une nouvelle mission
            $mission = new Mission();
            $mission->setEtat('non livré');
    
            // Récupérer le livreur ayant l'ID 75
            $livreur = $entityManager->getRepository(Livreur::class)->find($request->getSession()->get("id"));
    
            // Vérifier si le livreur existe
            if ($livreur) {
                // Associer la mission au livreur récupéré
                $mission->setLivreur($livreur);
            } else {
                // Gérer le cas où le livreur avec l'ID 75 n'existe pas
                // Vous pouvez afficher un message d'erreur ou prendre une autre action appropriée
            }
    
            // Enregistrer la mission dans la base de données
            $entityManager->persist($mission);
            $entityManager->flush();
    
            // Boucle sur les ID des commandes sélectionnées
            foreach ($selectedCommandIds as $commandId) {
                // Récupérer l'objet Commandes correspondant à partir de son ID
                $command = $entityManager->getRepository(Command::class)->find($commandId);
    
                // Vérifier si la commande existe
                if ($command) {
                    // Définir l'ID de la mission sur la commande
                    $command->setIdMission($mission);
    
                    // Mettre à jour la commande dans la base de données
                    $entityManager->persist($command);
                }
            }
    
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();
    
            // Rediriger vers la page d'accueil ou une autre page après la création de la mission
            return $this->redirectToRoute('app_mission_index');
        }
    
        // Si aucune commande n'a été sélectionnée, rediriger vers une page d'erreur ou afficher un message d'erreur
        // Dans cet exemple, rediriger simplement vers la page d'accueil
        return $this->redirectToRoute('app_mission_index');
    }
    
    
}
