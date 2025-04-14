<?php

namespace App\Controller;

use App\Entity\Assurance;
use App\Entity\Reservation;
use App\Form\AssuranceType;
use App\Repository\AssuranceRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UtilisateurRepository;

#[Route('/assurance')]
final class AssuranceController extends AbstractController
{
    #[Route(name: 'app_assurance_index', methods: ['GET'])]
    public function index(
        AssuranceRepository $assuranceRepository,
        SessionInterface $session,
        UtilisateurRepository $userRepo
    ): Response {
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('app_login');
        }
    
        $user = $userRepo->find($userId);
        if (!$user) {
            $session->clear();
            return $this->redirectToRoute('app_login');
        }
    
        // Si l'utilisateur est admin, on récupère toutes les assurances
        if ($user->getRole() === 'admin') {
            $assurances = $assuranceRepository->findAll();
        } else {
            // Sinon, on récupère seulement les assurances de l'utilisateur
            $assurances = $assuranceRepository->findByUser($user);
        }
    
        // Vérifier si l'utilisateur a le rôle admin pour choisir le template
        if ($user->getRole() === 'admin') {
            return $this->render('admin/assurances/index.html.twig', [
                'assurances' => $assurances,
            ]);
        }
    
        // Pour les utilisateurs normaux
        return $this->render('assurance/index.html.twig', [
            'assurances' => $assurances,
        ]);
    }

    #[Route('/new', name: 'app_assurance_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        ReservationRepository $reservationRepo
    ): Response {
        $assurance = new Assurance();
        
        // Si un ID de réservation est passé dans l'URL
        $reservationId = $request->query->get('reservation_id');
        if ($reservationId) {
            $reservation = $reservationRepo->find($reservationId);
            
            if ($reservation) {
                $assurance->setReservation($reservation);
            } else {
                $this->addFlash('error', 'La réservation spécifiée n\'existe pas');
                return $this->redirectToRoute('app_assurance_new');
            }
        }
    
        $form = $this->createForm(AssuranceType::class, $assurance);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($assurance);
                $entityManager->flush();
                $this->addFlash('success', 'Assurance créée avec succès');
                return $this->redirectToRoute('app_assurance_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création de l\'assurance');
            }
        }
    
        return $this->render('assurance/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_assurance_show', methods: ['GET'])]
    public function show(
        Assurance $assurance,
        SessionInterface $session,
        UtilisateurRepository $userRepo
    ): Response {
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('app_login');
        }
    
        $user = $userRepo->find($userId);
        if (!$user) {
            $session->clear();
            return $this->redirectToRoute('app_login');
        }
    
        // Vérifier si l'utilisateur a le rôle admin pour choisir le template
        if ($user->getRole() === 'admin') {
            return $this->render('admin/assurances/show.html.twig', [
                'assurance' => $assurance,
            ]);
        }
    
        // Vérification des permissions pour les utilisateurs normaux
        if (!$assurance->getReservation() || $assurance->getReservation()->getUtilisateur() !== $user) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }
    
        return $this->render('assurance/show.html.twig', [
            'assurance' => $assurance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_assurance_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Assurance $assurance, 
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        UtilisateurRepository $userRepo
    ): Response {
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepo->find($userId);
        if (!$user || !$assurance->getReservation() || $assurance->getReservation()->getUtilisateur() !== $user) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $form = $this->createForm(AssuranceType::class, $assurance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_assurance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('assurance/edit.html.twig', [
            'assurance' => $assurance,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_assurance_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Assurance $assurance, 
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        UtilisateurRepository $userRepo
    ): Response {
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepo->find($userId);
        if (!$user || !$assurance->getReservation() || $assurance->getReservation()->getUtilisateur() !== $user) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        if ($this->isCsrfTokenValid('delete'.$assurance->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($assurance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_assurance_index', [], Response::HTTP_SEE_OTHER);
    }
}