<?php

namespace App\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\SecurityBundle\Security;  // Correct import
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UtilisateurRepository;


use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\HotelRepository;
use App\Repository\CoworkingSpaceRepository;
use App\Repository\TransportMeanRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(
        Request $request,
        ReservationRepository $reservationRepository,
        SessionInterface $session,
        UtilisateurRepository $userRepo,
        PaginatorInterface $paginator
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
    
        // Récupérer toutes les réservations avec leurs relations
        $reservations = $reservationRepository->findAllWithDetails($user);
    
        // Ajout des filtres
        $filters = $request->query->all();
        $filteredReservations = $reservations;
    
        if (!empty($filters['type_service'])) {
            $filteredReservations = array_filter($filteredReservations, function ($reservation) use ($filters) {
                return $reservation->getTypeservice() === $filters['type_service'];
            });
        }
    
        if (!empty($filters['statut'])) {
            $filteredReservations = array_filter($filteredReservations, function ($reservation) use ($filters) {
                return $reservation->getStatut() === $filters['statut'];
            });
        }
    
        // Pagination
        $reservationsPaginated = $paginator->paginate(
            $filteredReservations,
            $request->query->getInt('page', 1),
            5
        );
    
        // Rendu
        if ($user->getRole() === 'admin') {
            return $this->render('admin/reservations/index.html.twig', [
                'reservations' => $reservationsPaginated,
                'current_filters' => $filters
            ]);
        }
    
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationsPaginated,
            'current_filters' => $filters
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        HotelRepository $hotelRepo,
        CoworkingSpaceRepository $coworkingRepo,
        TransportMeanRepository $transportRepo,
        SessionInterface $session,
        UtilisateurRepository $userRepo
    ): Response {
        $reservation = new Reservation();
        $reservation->setStatut('en attente');
        
        // Récupérer les IDs depuis l'URL
        $idhotel = $request->query->get('idhotel');
        $idspace = $request->query->get('idspace');
        $idtransport = $request->query->get('idtransport');
        
        // Initialiser les options du formulaire
        $formOptions = [
            'preselected_hotel' => null,
            'preselected_coworking' => null,
            'preselected_transport' => null,
            'preselected_service' => null,
            'require_nights' => true,
            'require_hours' => false
        ];
    
        // Gérer l'hôtel
        if ($idhotel) {
            $hotel = $hotelRepo->find($idhotel);
            if ($hotel) {
                $reservation->setHotel($hotel);
                $reservation->setTypeservice('Hôtel');
                $formOptions['preselected_hotel'] = $hotel;
                $formOptions['preselected_service'] = 'Hôtel';
            }
        }
        
        // Gérer l'espace de coworking
        if ($idspace) {
            $coworkingSpace = $coworkingRepo->find($idspace);
            if ($coworkingSpace) {
                $reservation->setCoworkingSpace($coworkingSpace);
                $formOptions['preselected_coworking'] = $coworkingSpace;
                
                if (!$idhotel) {
                    $reservation->setTypeservice('Coworking');
                    $formOptions['preselected_service'] = 'Coworking';
                    $formOptions['require_hours'] = true;
                    $formOptions['require_nights'] = false;
                }
            }
        }
        
        // Gérer le transport
        if ($idtransport) {
            $transport = $transportRepo->find($idtransport);
            if ($transport) {
                $reservation->setTransportMean($transport);
                $formOptions['preselected_transport'] = $transport;
            }
        }
        
        // Associer l'utilisateur connecté
        $userId = $session->get('user_id');
        if ($userId) {
            $user = $userRepo->find($userId);
            $reservation->setUtilisateur($user);
        }
    
        $form = $this->createForm(ReservationType::class, $reservation, $formOptions);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Contrôle de saisie manuel
            $errors = [];
            
            // Validation des nombres
            if ($formOptions['require_nights'] && $reservation->getNbrnuit() <= 0) {
                $errors[] = 'Le nombre de nuits doit être supérieur à 0.';
            }
            
            if ($formOptions['require_hours'] && $reservation->getNbrheure() <= 0) {
                $errors[] = 'Le nombre d\'heures doit être supérieur à 0.';
            }
            
            // Validation des champs obligatoires
            if (empty($reservation->getTypeservice())) {
                $errors[] = 'Le type de service est obligatoire.';
            }
            
            if (!$reservation->getHotel() && $formOptions['preselected_service'] === 'Hôtel') {
                $errors[] = 'L\'hôtel est obligatoire.';
            }
            
            if (!$reservation->getCoworkingSpace() && $formOptions['preselected_service'] === 'Coworking') {
                $errors[] = 'L\'espace de coworking est obligatoire.';
            }
            
            if (!$reservation->getTransportMean()) {
                $errors[] = 'Le moyen de transport est obligatoire.';
            }
    
            // Gestion des erreurs
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }
                
                return $this->render('reservation/new.html.twig', [
                    'reservation' => $reservation,
                    'form' => $form->createView(),
                ]);
            }
    
            // Si tout est OK
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            // Préparer le contenu de l'email directement dans le code
            $user = $reservation->getUtilisateur();
            $details = "Les détails de votre réservation :\n";
            $details .= "- Type de service : {$reservation->getTypeservice()}\n";
            if ($reservation->getHotel()) {
                $details .= "- Hôtel : {$reservation->getHotel()->getNom()}\n";
            }
            if ($reservation->getCoworkingSpace()) {
                $details .= "- Espace de coworking : {$reservation->getCoworkingSpace()->getNom()}\n";
            }
            if ($reservation->getTransportMean()) {
                $details .= "- Moyen de transport : {$reservation->getTransportMean()->getNom()}\n";
            }
            $details .= "- Statut : {$reservation->getStatut()}\n";
            $details .= "- Numéro de réservation : #{$reservation->getId()}\n";
    
            $htmlContent = "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Confirmation de réservation</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            h1 { color: #2c3e50; }
            .footer { margin-top: 20px; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Salut {$user->getPrenom()} {$user->getNom()},</h1>
            <p>" . nl2br(htmlspecialchars($details)) . "</p>
            <p class='footer'>Merci pour votre confiance en nous, vous pouvez maintenant passer à la procédure de paiement.</p>
        </div>
    </body>
    </html>
";

// Appel à l'API Mailgun avec cURL
$apiKey = 'VOTRE_CLE_API_MAILGUN'; // Remplace par ta clé API Mailgun
$domain = 'VOTRE_DOMAINE'; // Exemple : sandbox123.mailgun.org ou ton domaine vérifié
$url = "https://api.mailgun.net/v3/{$domain}/messages";

$data = [
    'from' => 'no-reply@votre-domaine.com',
    'to' => $user->getEmail(),
    'subject' => 'Confirmation de réservation',
    'html' => $htmlContent
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Mailgun utilise des données encodées en formulaire
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode("api:{$apiKey}")
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    $this->addFlash('success', 'Réservation créée et email envoyé avec succès.');
} else {
    $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email.');
}
    
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }


#[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
public function show(Reservation $reservation, SessionInterface $session, UtilisateurRepository $userRepo): Response
{
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
        return $this->render('admin/reservations/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    // Pour les utilisateurs normaux
    return $this->render('reservation/show.html.twig', [
        'reservation' => $reservation,
    ]);
}

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

   // src/Controller/ReservationController.php

// src/Controller/ReservationController.php

#[Route('/export-pdf', name: 'app_reservation_export_pdf')]
public function exportPdf(ReservationRepository $reservationRepo): Response
{
    // Configuration PDF
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($pdfOptions);

    // Récupération des données avec jointures
    $reservations = $reservationRepo->createQueryBuilder('r')
        ->leftJoin('r.hotel', 'h')
        ->leftJoin('r.coworkingSpace', 'c')
        ->leftJoin('r.transportMean', 't')
        ->addSelect(['h', 'c', 't'])
        ->getQuery()
        ->getResult();

    // Génération HTML via Twig
    $html = $this->renderView('reservation/export-pdf.html.twig', [
        'reservations' => $reservations
    ]);

    // Génération PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    // Envoi du PDF
    $filename = 'reservations-export-' . date('Y-m-d') . '.pdf';
    
    return new Response(
        $dompdf->output(),
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]
    );
}

}
