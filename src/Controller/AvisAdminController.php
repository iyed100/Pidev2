<?php
namespace App\Controller;

use App\Entity\Claim;
use App\Entity\Response as ResponseEntity;
use App\Form\ResponseType;
use App\Repository\AvisRepository;
use App\Repository\ClaimRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisAdminController extends AbstractController
{
    #[Route('/admin/avis', name: 'admin_avis')]
public function index(
    AvisRepository $avisRepository, 
    ClaimRepository $claimRepository,
    FormFactoryInterface $formFactory
): Response {
    $claims = $claimRepository->findAll();
    
    // Create response forms for each claim
    $responseForms = [];
foreach ($claims as $claim) {
    $response = new ResponseEntity();
    $form = $this->createForm(ResponseType::class, $response, [
        'action' => $this->generateUrl('admin_claim_response', ['id' => $claim->getId()]),
        'method' => 'POST'
    ]);
    $responseForms[$claim->getId()] = $form->createView();
}
    
    return $this->render('admin/AvisAdmin.html.twig', [
        'avis' => $avisRepository->findAll(),
        'claims' => $claims,
        'responseForms' => $responseForms
    ]);
}

    #[Route('/admin/claim/{id}/status', name: 'admin_claim_status', methods: ['POST'])]
    public function updateStatus(
        Request $request,
        Claim $claim,
        EntityManagerInterface $entityManager
    ): Response {
        $newStatus = $request->request->get('status');
        if ($this->isCsrfTokenValid('status'.$claim->getId(), $request->request->get('_token'))) {
            $claim->setStatus($newStatus);
            $entityManager->flush();
            $this->addFlash('success', 'Status updated successfully!');
        }
        return $this->redirectToRoute('admin_avis');
    }

    #[Route('/admin/claim/{id}/response', name: 'admin_claim_response', methods: ['POST'])]
public function addResponse(
    Request $request,
    Claim $claim,
    EntityManagerInterface $entityManager
): Response {
    $response = new ResponseEntity();
    $form = $this->createForm(ResponseType::class, $response);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        try {
            $response->setClaim($claim);
            $response->setCreatedAt(new \DateTime());
            
            $entityManager->persist($response);
            $entityManager->flush();
            
            $this->addFlash('success', 'Response added successfully!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error saving response: '.$e->getMessage());
        }
    } else {
        // Add more detailed error information
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        $this->addFlash('error', 'Invalid form submission: ' . implode(', ', $errors));
    }

    return $this->redirectToRoute('admin_avis');
}
}