<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\Commandes;
use App\Controller\CommandesController;

use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
#[Route('/mission')]
class MissionController extends AbstractController
{
    #[Route('/', name: 'app_mission_index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository): Response
    {
        return $this->render('mission/index.html.twig', [
            'missions' => $missionRepository->findAll(),
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
    
            // Enregistrer la mission dans la base de données
            $entityManager->persist($mission);
            $entityManager->flush();
    
            // Boucle sur les ID des commandes sélectionnées
            foreach ($selectedCommandIds as $commandId) {
                // Récupérer l'objet Commandes correspondant à partir de son ID
                $commande = $entityManager->getRepository(Commandes::class)->find($commandId);
    
                // Vérifier si la commande existe
                if ($commande) {
                    // Set the mission ID on the command entity
                    $commande->setIdMission($mission->getIdMission());
    
                    // Mettre à jour la commande dans la base de données
                    $entityManager->persist($commande);
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
