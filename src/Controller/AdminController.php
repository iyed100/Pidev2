<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UtilisateurRepository;
use Knp\Snappy\Pdf;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function dashboard(SessionInterface $session, UtilisateurRepository $userRepository): Response
    {
        // Check if a user is logged in via session
        if (!$session->has('user_id')) {
            return $this->redirectToRoute('app_login_back');
        }

        $userId = $session->get('user_id');
        $user = $userRepository->find($userId);

        // If user not found or not an admin, destroy session and redirect
        if (!$user || $user->getRole() !== 'admin') {
            $session->invalidate(); // Clear the session completely
            return $this->redirectToRoute('app_login_back');
        }

        // User is an admin — allow access to dashboard
        return $this->render('admin/dashboard.html.twig', [
            'user' => $user
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

    #[Route('/generate-pdf', name: 'generate_pdf')]
    public function generatePdf(Pdf $pdf, UtilisateurRepository $userRepository): Response
    {
        // Fetch all users from the repository
        $utilisateurs = $userRepository->findAll();

        // Render the HTML content for the PDF
        $html = $this->renderView('pdf_template.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);

        // Generate PDF from the HTML content
        $pdfContent = $pdf->getOutputFromHtml($html);

        // Return the PDF as a response
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="users_list.pdf"',
        ]);
    }
}
