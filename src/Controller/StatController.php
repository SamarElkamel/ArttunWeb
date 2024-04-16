<?php
// src/Controller/StatController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MissionRepository;
use App\Repository\LivreurRepository;
use App\Repository\CommandesRepository;

class StatController extends AbstractController
{
    private $missionRepository;
    private $livreurRepository;
    private $commandesRepository;

    public function __construct(MissionRepository $missionRepository, LivreurRepository $livreurRepository, CommandesRepository $commandesRepository)
    {
        $this->missionRepository = $missionRepository;
        $this->livreurRepository = $livreurRepository;
        $this->commandesRepository = $commandesRepository;
    }

    /**
     * @Route("/stats", name="stats")
     */
    public function index(): Response
    {
        $nbMissions = $this->missionRepository->count([]);
        $nbLivreurs = $this->livreurRepository->count([]); // Définissez cette variable
        $nbCommandes = $this->commandesRepository->count([]);

        // Supposons que vous vouliez compter le nombre de missions avec l'état "En cours"
        $etat = 'En cours';
        $nbMissionsEnCours = $this->missionRepository->countByEtat($etat);

        return $this->render('livreur/stat.html.twig', [
            'nbMissions' => $nbMissions,
            'nbLivreurs' => $nbLivreurs,
            'nbCommandes' => $nbCommandes,
            'nbMissionsEnCours' => $nbMissionsEnCours,
        ]);
    }
}
