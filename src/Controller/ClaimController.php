<?php
// src/Controller/ClaimController.php
namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Claim;
use App\Entity\Response as ResponseEntity;
use App\Form\AvisType;
use App\Form\ClaimType;
use App\Form\ResponseType;
use App\Form\SearchAvisType;
use App\Repository\AvisRepository;
use App\Repository\ClaimRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/claim')]
class ClaimController extends AbstractController
{
    #[Route('/', name: 'claim_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ClaimRepository $claimRepository, EntityManagerInterface $entityManager): HttpResponse
    {
        $claim = new Claim();
        $form = $this->createForm(ClaimType::class, $claim);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($claim);
            $entityManager->flush();
            $this->addFlash('success', 'Claim created successfully!');
            return $this->redirectToRoute('claim_index');
        }

        return $this->render('avis/index.html.twig', [
            'claims' => $claimRepository->findAll(),
        'claim_form' => $form->createView(),
        'editing_claim' => null, // Explicitly set to null
        'section_title' => 'Claims Management',
        'section_description' => 'Manage all your claims in one place',
        'response_form' => null, // Add this to avoid undefined variable
        'search_form' => null, // Add this to avoid undefined variable
        'edit_claim_form' => null // Add this to avoid undefined variable
        ]);
    }

    #[Route('/{id}/edit', name: 'claim_edit', methods: ['GET', 'POST'])]
public function edit(
    Request $request,
    Claim $claim,
    EntityManagerInterface $entityManager,
    ClaimRepository $claimRepository,
    AvisRepository $avisRepository
): HttpResponse {
    $form = $this->createForm(ClaimType::class, $claim);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', 'Claim updated successfully!');
        return $this->redirectToRoute('avis_index');
    }

    // Initialize forms
    $searchForm = null;
    $responseForm = $this->createForm(ResponseType::class, new ResponseEntity());
    
    try {
        if (class_exists(SearchAvisType::class)) {
            $searchForm = $this->createForm(SearchAvisType::class)->createView();
        }
    } catch (\Exception $e) {
        // Silently handle if form cannot be created
    }

    return $this->render('avis/index.html.twig', [
        'avis' => $avisRepository->findAll(),
        'avis_form' => $this->createForm(AvisType::class, new Avis())->createView(),
        'claims' => $claimRepository->findAll(),
        'claim_form' => $this->createForm(ClaimType::class, new Claim())->createView(),
        'editing_claim' => $claim, // Make sure this is never null
        'edit_claim_form' => $form->createView(),
        'search_form' => $searchForm,
        'response_form' => $responseForm->createView(),
        'section_title' => 'Claims Management',
        'section_description' => 'Manage all your claims in one place'
    ]);
}

#[Route('/{id}', name: 'claim_delete', methods: ['POST'])]
public function delete(
    Request $request, 
    Claim $claim, 
    EntityManagerInterface $entityManager
): HttpResponse {
    $referer = $request->headers->get('referer');
    $isAdmin = str_contains($referer, '/admin/');

    if ($this->isCsrfTokenValid('delete'.$claim->getId(), $request->request->get('_token'))) {
        foreach ($claim->getResponses() as $response) {
            $entityManager->remove($response);
        }
        $entityManager->remove($claim);
        $entityManager->flush();
        $this->addFlash('success', 'Claim deleted successfully!');
    }

    return $this->redirect($isAdmin ? $this->generateUrl('admin_avis') : $this->generateUrl('avis_index'));
}

    #[Route('/{id}/response', name: 'claim_add_response', methods: ['POST'])]
public function addResponse(
    Request $request,
    Claim $claim,
    EntityManagerInterface $entityManager,
    ValidatorInterface $validator
): HttpResponse {
    $response = new ResponseEntity();
    $response->setClaim($claim); // Set claim first to ensure relation exists
    $response->setCreatedAt(new \DateTime()); // Set timestamp
    
    $form = $this->createForm(ResponseType::class, $response);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        $errors = $validator->validate($response);
        
        if (count($errors) === 0) {
            try {
                $entityManager->persist($response);
                $entityManager->flush();
                $this->addFlash('success', 'Response added successfully!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Failed to save response: '.$e->getMessage());
            }
        } else {
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }
    }

    return $this->redirectToRoute('claim_edit', ['id' => $claim->getId()]);
}
}