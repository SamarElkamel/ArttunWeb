<?php

namespace App\Controller;

use App\Entity\Command;
use App\Form\CommandType;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\High;


#[Route('/command')]
class CommandController extends AbstractController
{
                
      #[Route('/', name: 'app_command_index', methods: ['GET'])]
                public function index(CommandRepository $commandRepository): Response
                {
                    $commands = $commandRepository->findAll();
                    // Prepare data for the line chart
                    // Prepare data for the line chart
                    $monthlyCounts = [];
                    foreach ($commands as $command) {
                        $monthYear = $command->getDate()->format('Y-m');
                        if (!isset($monthlyCounts[$monthYear])) {
                            $monthlyCounts[$monthYear] = 0;
                        }
                        $monthlyCounts[$monthYear]++;
                    }
                    return $this->render('command/index.html.twig', [
                        'commands' => $commands,
                        'monthlyCounts' => $monthlyCounts,
                       
                    ]);
                }
                    

    
    
    
    
    #[Route('/Show', name: 'app_command_show', methods: ['GET'])]
     public function show(CommandRepository $commandRepository): Response
                {
                    $commands = $commandRepository->findAll();
                    

                    return $this->render('command/show.html.twig', [
                        'commands' => $commands,
                       
                    ]);
                }

    #[Route('/new', name: 'app_command_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $command = new Command();
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($command);
            $entityManager->flush();

            return $this->redirectToRoute('app_command_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('command/new.html.twig', [
            'command' => $command,
            'form' => $form,
        ]);
    }

    #[Route('/stat', name: 'app_command_stat',methods: ['GET'])]
    public function stat(CommandRepository $commandRepository): Response
    {
       
        return $this->render('command/stat.html.twig');
    }
    #[Route('/stat2', name: 'app_command_stat_2')]
    public function stat2(): Response
    {
       
        return $this->render('command/stat2.html.twig');
    }
    

    
    

    #[Route('/{id}/edit', name: 'app_command_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Command $command, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_command_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('command/edit.html.twig', [
            'command' => $command,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_command_delete', methods: ['POST'])]
    public function delete(Request $request, Command $command, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$command->getId(), $request->request->get('_token'))) {
            $entityManager->remove($command);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_command_index', [], Response::HTTP_SEE_OTHER);
    }
    /*#[Route('/QrCode/{ref}', name: 'app_QrCode')]
    public function qrGenerator(String $ref, CommandRepository $commandRepository)
    {
        $command = $commandRepository->findOneBy(['reference' => $ref]);
    
        if (!$command) {
            throw $this->createNotFoundException('Command not found');
        }
    
        $qrcode = QrCode::create("- qr code :" . $command->getReference())
            ->setSize(300)
            ->setMargin(10)
            ->setForegroundColor(new Rgb(0, 0, 0))
            ->setBackgroundColor(new Rgb(255, 255, 255));
        $writer = new PngWriter($qrcode);
        $qrCodeImage = $writer->writeString(); // Assurez-vous que la variable est nommée correctement
    
        return $this->render('command/index.html.twig', [
            'qrCodeImage' => $qrCodeImage, // Utilisez le nom correct de la variable
            'command' => $command,
        ]);
    }*/
    
    

   
}
