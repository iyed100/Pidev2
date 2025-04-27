<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\AssuranceRepository;
use App\Repository\ReservationRepository;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(
        SessionInterface $session, 
        UtilisateurRepository $userRepository,
        AssuranceRepository $assuranceRepository,
        ReservationRepository $reservationRepository
    ): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$session->has('user_id')) {
            return $this->redirectToRoute('app_login_back');
        }

        $userId = $session->get('user_id');
        $user = $userRepository->find($userId);

        // Vérifie si l'utilisateur est admin
        if (!$user || $user->getRole() !== 'admin') {
            $session->invalidate();
            return $this->redirectToRoute('app_login_back');
        }

        // Données pour les assurances par type
        $assurancesByType = $assuranceRepository->countByType();
        $assuranceLabels = [];
        $assuranceData = [];
        if (empty($assurancesByType)) {
            $assuranceLabels = ['Aucune donnée'];
            $assuranceData = [0];
        } else {
            foreach ($assurancesByType as $item) {
                $assuranceLabels[] = $item['type'] ?? 'Inconnu';
                $assuranceData[] = (int)($item['count'] ?? 0);
            }
        }

        // Données pour les réservations par hôtel
        $reservationsByHotel = $reservationRepository->countByHotel();
        $hotelLabels = [];
        $hotelData = [];
        if (empty($reservationsByHotel)) {
            $hotelLabels = ['Aucune donnée'];
            $hotelData = [0];
        } else {
            foreach ($reservationsByHotel as $item) {
                $hotelLabels[] = $item['hotel_name'] ?? 'Inconnu';
                $hotelData[] = (int)($item['count'] ?? 0);
            }
        }

        // Données pour les assurances par statut
        $assurancesByStatus = $assuranceRepository->countByStatus();
        $statusLabels = [];
        $statusData = [];
        foreach ($assurancesByStatus as $item) {
            $statusLabels[] = $item['status'] ?? 'Inconnu';
            $statusData[] = (int)($item['count'] ?? 0);
        }

        // Données pour les réservations par statut
        $reservationsByStatus = $reservationRepository->countByStatus();
        $reservationStatusLabels = [];
        $reservationStatusData = [];
        foreach ($reservationsByStatus as $item) {
            $reservationStatusLabels[] = $item['status'] ?? 'Inconnu';
            $reservationStatusData[] = (int)($item['count'] ?? 0);
        }

        // Données pour les assurances par client
        $assurancesByClient = $assuranceRepository->countByClient();
        $clientLabels = [];
        $clientData = [];
        if (empty($assurancesByClient)) {
            $clientLabels = ['Aucun client'];
            $clientData = [0];
        } else {
            foreach ($assurancesByClient as $item) {
                $clientLabels[] = $item['client_name'] ?? 'Client inconnu';
                $clientData[] = (int)($item['count'] ?? 0);
            }
        }

        // Données pour les réservations par client
        $reservationsByClient = $reservationRepository->countByClient();
        $reservationClientLabels = [];
        $reservationClientData = [];
        if (empty($reservationsByClient)) {
            $reservationClientLabels = ['Aucun client'];
            $reservationClientData = [0];
        } else {
            foreach ($reservationsByClient as $item) {
                $reservationClientLabels[] = $item['client_name'] ?? 'Client inconnu';
                $reservationClientData[] = (int)($item['count'] ?? 0);
            }
        }

        // Données pour les réservations par espace de coworking
        $reservationsByCoworking = $reservationRepository->countByCoworking();
        $coworkingLabels = [];
        $coworkingData = [];
        if (empty($reservationsByCoworking)) {
            $coworkingLabels = ['Aucun espace'];
            $coworkingData = [0];
        } else {
            foreach ($reservationsByCoworking as $item) {
                $coworkingLabels[] = $item['coworking_name'] ?? 'Espace inconnu';
                $coworkingData[] = (int)($item['count'] ?? 0);
            }
        }

        // Données pour les réservations par transport
        $reservationsByTransport = $reservationRepository->countByTransport();
        $transportLabels = [];
        $transportData = [];
        if (empty($reservationsByTransport)) {
            $transportLabels = ['Aucun transport'];
            $transportData = [0];
        } else {
            foreach ($reservationsByTransport as $item) {
                $transportLabels[] = $item['transport_name'] ?? 'Transport inconnu';
                $transportData[] = (int)($item['count'] ?? 0);
            }
        }

        return $this->render('admin/dashboard.html.twig', [
            'user' => $user,
            'assurances_count' => $assuranceRepository->count([]),
            'reclamations_count' => $reservationRepository->count([]),
            'chart_labels' => $assuranceLabels,
            'chart_data' => $assuranceData,
            'hotel_labels' => $hotelLabels,
            'hotel_data' => $hotelData,
            'status_labels' => $statusLabels,
            'status_data' => $statusData,
            'reservation_status_labels' => $reservationStatusLabels,
            'reservation_status_data' => $reservationStatusData,
            'client_labels' => $clientLabels,
            'client_data' => $clientData,
            'reservation_client_labels' => $reservationClientLabels,
            'reservation_client_data' => $reservationClientData,
            'coworking_labels' => $coworkingLabels,
            'coworking_data' => $coworkingData,
            'transport_labels' => $transportLabels,
            'transport_data' => $transportData
        ]);
    }
    #[Route('/loginback', name: 'app_login_back')]
    public function login(Request $request, UtilisateurRepository $userRepository, SessionInterface $session): Response
    {
        // Redirection si déjà connecté
        if ($session->has('user_id')) {
            return $this->redirectToRoute('app_acceuil');
        }

        $error = null;
        $email = '';

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                // Check if password matches (plain text check for now)
                if ($user->getPassword() === $password) {
                    // Check if user is admin
                    if ($user->getRole() === 'admin') {
                        $session->set('user_id', $user->getId());

                        return $this->redirectToRoute('admin_dashboard');
                    } else {
                        $error = 'Accès refusé. Vous devez être un administrateur.';
                    }
                } else {
                    $error = 'Mot de passe incorrect.';
                }
            } else {
                $error = 'Email introuvable.';
            }
        }

        return $this->render('admin/login.html.twig', [
            'last_username' => $email,
            'error' => $error
        ]);
    }

    #[Route('/logoutback', name: 'app_logout_back')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('app_login_back');
    }

    #[Route('/admin/users', name: 'userList')]
    public function userList(SessionInterface $session, UtilisateurRepository $userRepository): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$session->has('user_id')) {
            return $this->redirectToRoute('app_login_back');
        }

        $user = $userRepository->find($session->get('user_id'));

        // Vérifie si l'utilisateur est bien admin
        if (!$user || $user->getRole() !== 'admin') {
            $session->invalidate();
            return $this->redirectToRoute('app_login_back');
        }

        // Récupère tous les utilisateurs
        $utilisateurs = $userRepository->findAll();

        return $this->render('admin/user_list.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
}
