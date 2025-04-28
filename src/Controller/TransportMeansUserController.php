<?php

namespace App\Controller;

use App\Repository\TransportMeansRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transport')]
class TransportMeansUserController extends AbstractController
{
    #[Route('/', name: 'app_transport_means_user_index', methods: ['GET'])]
    public function index(TransportMeansRepository $transportMeansRepository): Response
    {
        return $this->render('transport_means/user_index.html.twig', [
            'transport_means' => $transportMeansRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_transport_means_user_show', methods: ['GET'])]
    public function show(int $id, TransportMeansRepository $transportMeansRepository): Response
    {
        $transport_mean = $transportMeansRepository->find($id);

        if (!$transport_mean) {
            throw $this->createNotFoundException('Le moyen de transport demandé n\'existe pas.');
        }

        return $this->render('transport_means/user_show.html.twig', [
            'transport_mean' => $transport_mean,
        ]);
    }

    #[Route('/gestion', name: 'app_transport_means_user_management', methods: ['GET'])]
    public function management(): Response
    {
        return $this->redirectToRoute('app_transport_means_user_index');
    }
} 