<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\SecurityBundle\Security;  // Correct import
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'app_acceuil')]
    public function index(SessionInterface $session, UtilisateurRepository $userRepository): Response
    {
        $user = null;

        if ($session->has('user_id')) {
            $user = $userRepository->find($session->get('user_id'));
        }

        // Section Offres Spéciales
        $offres = [
            [
                'ville' => 'Lisbon, Portugal',
                'notation' => 4,
                'description' => '5 nights and 4 days in 5-star hotels, breakfast and lunch included. Explore Renaissance heritage in classical literature.',
                'prix' => '500',
                'highlight' => true,
                'image' => 'lisbon.jpg',
                'titre_droite' => 'Special Europe'
            ],
            [
                'ville' => 'Athens, Greece',
                'notation' => 4,
                'description' => '5 nights and 4 days in 5-star hotels, breakfast and lunch included. Walk through ancient history.',
                'prix' => '800',
                'image' => 'grece.jpg',
                'titre_droite' => 'Classic Destinations'
            ],
            [
                'ville' => 'Rome, Italy',
                'notation' => 5,
                'description' => '5 nights and 4 days in 5-star hotels, breakfast and lunch included. Discover the heart of the Renaissance.',
                'prix' => '750',
                'highlight' => true,
                'image' => 'rome.jpg',
                'titre_droite' => 'Summer Promo'
            ]
        ];

        // Section Événements
        $evenements = [
            [
                'titre' => 'Paris City Tour',
                'notation' => 5,
                'duree' => '7 Days tour',
                'prix' => '350€/Day',
                'description' => 'Découvrez les charmes de Paris avec notre visite guidée exclusive',
                'tag' => 'GUIDED TOUR',
                'image' => 'paris-tour.jpg'
            ],
            [
                'titre' => 'Gastronomic Week',
                'notation' => 4,
                'duree' => '5 Days tour',
                'prix' => '420€/Day',
                'description' => 'Voyage culinaire à travers les meilleurs restaurants étoilés',
                'tag' => 'FOOD EXPERIENCE',
                'image' => 'gastronomy.jpg'
            ]
        ];

        // Section Destinations Populaires
        $destinations = [
            [
                'nom' => 'Monument of Berlin',
                'ville' => 'Berlin, Germany',
                'image' => 'berlin.jpg',
                'notation' => 4.5
            ],
            [
                'nom' => 'Midsummer Bridge',
                'ville' => 'London, United Kingdom',
                'image' => 'london-bridge.jpg',
                'notation' => 4.7
            ],
            [
                'nom' => 'Rialto Bridge',
                'ville' => 'Venice, Italy',
                'image' => 'rialto.jpg',
                'notation' => 4.9
            ],
            [
                'nom' => 'Sea of Our Logo',
                'ville' => 'Logo',
                'image' => 'logo-sea.jpg',
                'notation' => 4.3
            ],
            [
                'nom' => 'Eiffel Tower',
                'ville' => 'Paris, France',
                'image' => 'paris.jpg',
                'notation' => 4.8
            ]
        ];

        return $this->render('acceuil/index.html.twig', [
            // Variables globales
            'message' => 'Office Speciale',
            'citation' => "Dans 20 ans, vous avez encore plus déçu par les choses que vous n'avez pas faites. Arrêtez de regretter et commencez à voyager.",
            'pop_dest_text' => "Destinations les plus populaires à travers le monde, des lieux historiques aux merveilles naturelles.",
            'user' => $user,

            // Données des sections
            'offres' => $offres,
            'evenements' => $evenements,
            'destinations' => $destinations
        ]);
    }


    #[Route('/signup', name: 'app_register')]
public function register(
    Request $request,
    EntityManagerInterface $entityManager
): Response {
    $user = new Utilisateur();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérification du CAPTCHA
        $recaptchaToken = $request->request->get('g-recaptcha-response');
        if (!$this->verifyRecaptcha($recaptchaToken)) {
            $this->addFlash('error', 'Veuillez compléter le CAPTCHA');
            return $this->redirectToRoute('app_register');
        }

        // Encode the plain password
        $user->setPassword($form->get('password')->getData());
        $user->setRole('client');
        $user->setCreatedAt(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Registration successful! You can now login.');
        return $this->redirectToRoute('app_login');
    }

    return $this->render('acceuil/signup.html.twig', [
        'registrationForm' => $form->createView(),
        'recaptcha_site_key' => '6LcgzCYrAAAAAJOpXKQxI321w7DEAcdSZ1g11baa' // Remplacez par votre clé
    ]);
}

private function verifyRecaptcha(string $token): bool
{
    $secret = '6LcgzCYrAAAAAExXIw3LiwPmIem_BfnUUfj7I3Hm'; // Remplacez par votre clé secrète
    
    $response = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$token}"
    );
    
    $responseData = json_decode($response);
    return $responseData->success;
}
    #[Route('/emailInput', name: 'emailInput')]
    public function emailInput(Request $request, UtilisateurRepository $userRepository, SessionInterface $session): Response
    {
        if ($session->has('user_id')) {
            return $this->redirectToRoute('app_acceuil');
        }

        $error = null;
        $email = '';

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $error = 'Veuillez vérifier votre email';
            } else {
                $session->set('reset_email', $email);
                return $this->redirectToRoute('passReset');
            }
        }

        return $this->render('acceuil/emailinput.html.twig', [
            'last_username' => $email,
            'error' => $error
        ]);
    }


    #[Route('/passReset', name: 'passReset')]
    public function passReset(Request $request, UtilisateurRepository $userRepository, SessionInterface $session, MailerInterface $mailer): Response
    {
        if ($session->has('user_id')) {
            return $this->redirectToRoute('app_acceuil');
        }

        $email = $session->get('reset_email');

        if (!$email) {
            return $this->redirectToRoute('emailInput');
        }

        $resetCode = random_int(100000, 999999);
        $session->set('reset_code', $resetCode);

        $emailMessage = (new Email())
            ->from('alastouri@gmail.com')
            ->to($email)
            ->subject('Réinitialisation de mot de passe')
            ->html("<p>Voici votre code de réinitialisation : <strong>$resetCode</strong></p>");

        try {
            $mailer->send($emailMessage);
        } catch (TransportExceptionInterface $e) {
            dump($e->getMessage()); // or use logger
            die(); // temporarily stop here to debug
        }

        return $this->render('acceuil/passreset.html.twig', [
            'last_username' => $email,
            'error' => null,
            'code' => $resetCode,
        ]);
    }

    #[Route('/handlePassReset', name: 'handlePassReset', methods: ['POST'])]
    public function handlePassReset(
        Request $request,
        UtilisateurRepository $userRepository,
        EntityManagerInterface $em,
        SessionInterface $session
    ): Response {
        $email = $request->request->get('email');
        $newPassword = $request->request->get('new_password');
        $confirmPassword = $request->request->get('confirm_password');

        if ($newPassword !== $confirmPassword) {
            return new Response('Les mots de passe ne correspondent pas.', 400);
        }

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new Response('Utilisateur non trouvé.', 404);
        }

        // Hash the password using Symfony's hasher
        $hashedPassword =  $newPassword;
        $user->setPassword($hashedPassword);

        $em->flush();

        // Clear reset session data
        $session->remove('reset_code');
        $session->remove('reset_email');

        // Redirect to login or success page
        return $this->redirectToRoute('app_login');
    }


    #[Route('/login', name: 'app_login')]
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

            // Comparaison simple du mot de passe (non chiffré)
            if ($user && $user->getPassword() === $password) {
                // Démarrer une session manuelle
                $session->set('user_id', $user->getId());

                return $this->redirectToRoute('app_acceuil');
            }

            $error = 'Identifiants invalides, veuillez réessayer.';
        }

        return $this->render('acceuil/login.html.twig', [
            'last_username' => $email,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('app_login');
    }


    #[Route('/update-user', name: 'app_update_user', methods: ['POST'])]
public function updateUser(Request $request, SessionInterface $session, UtilisateurRepository $userRepository, EntityManagerInterface $em, ValidatorInterface $validator): Response
{
    $user = $userRepository->find($session->get('user_id'));
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Mise à jour des valeurs
    $user->setNom($request->request->get('nom'));
    $user->setPrenom($request->request->get('prenom'));
    $user->setAge((int) $request->request->get('age'));
    $user->setEmail($request->request->get('email'));

    // Si mot de passe rempli
    if ($request->request->get('password')) {
        $user->setPassword($request->request->get('password'));
    }

    $errors = $validator->validate($user);
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            $this->addFlash('error', $error->getMessage());
        }
        return $this->redirectToRoute('app_client_account');
    }

    $em->flush();
    $this->addFlash('success', 'Vos informations ont été mises à jour avec succès');
    return $this->redirectToRoute('app_client_account');
}

#[Route('/delete-user', name: 'app_delete_user', methods: ['POST'])]
public function deleteUser(SessionInterface $session, UtilisateurRepository $userRepository, EntityManagerInterface $em): Response
{
    $userId = $session->get('user_id');
    if (!$userId) {
        return $this->redirectToRoute('app_login');
    }

    $user = $userRepository->find($userId);
    if ($user) {
        $em->remove($user);
        $em->flush();
    }

    $session->clear();
    $this->addFlash('success', 'Votre compte a été supprimé avec succès');
    return $this->redirectToRoute('app_login');
}

#[Route('/mon-compte', name: 'app_client_account')]
public function clientAccount(SessionInterface $session, UtilisateurRepository $userRepository): Response
{
    if (!$session->has('user_id')) {
        return $this->redirectToRoute('app_login');
    }

    $user = $userRepository->find($session->get('user_id'));
    
    return $this->render('acceuil/client_dashboard.html.twig', [
        'user' => $user,
        'section' => 'account' // Pour afficher la bonne section
    ]);
}

#[Route('/dashboard-client', name: 'app_client_dashboard')]
public function clientDashboard(SessionInterface $session, UtilisateurRepository $userRepository): Response
{
    // Vérification de la connexion
    if (!$session->has('user_id')) {
        return $this->redirectToRoute('app_login');
    }

    $user = $userRepository->find($session->get('user_id'));

    return $this->render('acceuil/client_dashboard.html.twig', [
        'user' => $user,
        'section' => 'dashboard' // Pour la navigation
    ]);
}
}
