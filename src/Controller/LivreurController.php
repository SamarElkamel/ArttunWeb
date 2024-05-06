<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Repository\LivreurRepository;
use App\Repository\MissionRepository;
use App\Repository\CommandesRepository;

use App\Form\LivreurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Knp\Component\Pager\PaginatorInterface;
#[Route('/livreur')]
class LivreurController extends AbstractController
{
    #[Route('/', name: 'app_livreur_index', methods: ['GET'])]
    public function index(LivreurRepository $livreurRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Récupérez les données à paginer (dans ce cas, tous les livreurs)
        $livreursQuery = $livreurRepository->createQueryBuilder('l')
            ->getQuery();

        // Paginer les données
        $livreurs = $paginator->paginate(
            $livreursQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page par défaut
            2 // Limite d'éléments par page
        );

        // Passez les données paginées à la vue
        return $this->render('livreur/index.html.twig', [
            'livreurs' => $livreurs,
        ]);
    }
   
    #[Route('/stat', name: 'app_livreur_stat')]
    public function stat(
        LivreurRepository $livreurRepository,
        MissionRepository $missionRepository,
        CommandesRepository $commandeRepository
    ): Response {
        // Récupérer le nombre total de livreurs
        $nombreLivreurs = count($livreurRepository->findAll());

        // Récupérer le nombre total de missions
        $nombreMissions = count($missionRepository->findAll());

        // Récupérer le nombre total de commandes
        $nombreCommandes = count($commandeRepository->findAll());

        $nombreMissionsLivre = $missionRepository->countByEtat('livré');

        // Calculer le nombre de missions non livrées
        $nombreMissionsNonLivre = $nombreMissions - $nombreMissionsLivre;

        // Préparer les données pour le graphique
        $data = [
            'labels' => ['Livreurs', 'Missions', 'Commandes', 'Missions livrées', 'Missions non livrées'],
            'values' => [
                $nombreLivreurs,
                $nombreMissions,
                $nombreCommandes,
                $nombreMissionsLivre,
                $nombreMissionsNonLivre
            ]
        ];

        return $this->render('livreur/stat.html.twig', [
            'data' => $data,
            'nombreLivreurs' => $nombreLivreurs,
            'nombreMissions' => $nombreMissions,
            'nombreCommandes' => $nombreCommandes,
            'nombreMissionsLivre' => $nombreMissionsLivre,
            'nombreMissionsNonLivre' => $nombreMissionsNonLivre,
        ]);
    }
    #[Route('/new', name: 'app_livreur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livreur = new Livreur();
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form['photo']->getData();
            if ($pictureFile) {
                $filename = md5(uniqid()) . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory'),
                    $filename
                );
                $livreur->setPhoto($filename);
            }
            $entityManager->persist($livreur);
            $entityManager->flush();

            return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livreur/new.html.twig', [
            'livreur' => $livreur,
            'form' => $form,
        ]);
    }

 

    #[Route('/{id}/edit', name: 'app_livreur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livreur $livreur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form['photo']->getData();
            if ($pictureFile) {
                $filename = md5(uniqid()) . '.' . $pictureFile->guessExtension();
                $pictureFile->move(
                    $this->getParameter('pictures_directory'),
                    $filename
                );
                $livreur->setPhoto($filename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livreur/edit.html.twig', [
            'livreur' => $livreur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livreur_delete', methods: ['POST'])]
    public function delete(Request $request, Livreur $livreur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livreur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livreur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
    }


   
    
}









