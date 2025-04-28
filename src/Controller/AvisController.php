<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Claim;
use App\Form\AvisType;
use App\Form\ClaimType;
use App\Form\SearchAvisType; // Add this use statement
use App\Repository\AvisRepository;
use App\Repository\ClaimRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Security;

#[Route('/avis')]
class AvisController extends AbstractController
{
    #[Route('/', name: 'avis_index', methods: ['GET', 'POST'])]
public function index(
    Request $request,
    AvisRepository $avisRepository,
    ClaimRepository $claimRepository,
    EntityManagerInterface $entityManager,
    Security $security
): Response {
    // Create search form
    $searchForm = $this->createForm(SearchAvisType::class);
    $searchForm->handleRequest($request);

    // Create avis form
    $avi = new Avis();
    $avisForm = $this->createForm(AvisType::class, $avi);
    $avisForm->handleRequest($request);

    if ($avisForm->isSubmitted()) {
        if ($avisForm->isValid()) {
            try {
                $entityManager->persist($avi);
                $entityManager->flush();
                $this->addFlash('success', 'Avis created successfully!');
                return $this->redirectToRoute('avis_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error saving: '.$e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Please fix the errors below');
            // You can also dump errors for debugging:
            // dump($avisForm->getErrors(true, true));
        }
    }
    $averageNote = $avisRepository->getAverageNote();

    // Create claim form
    $claim = new Claim();
    $claim->setStatus('En attente')
          ->setCdate(new \DateTime());
    
    $claimForm = $this->createForm(ClaimType::class, $claim);
    $claimForm->handleRequest($request);

    if ($claimForm->isSubmitted() && $claimForm->isValid()) {
        // No user ID needed, or set to a default value if required by your entity
        // $claim->setUserId(0); // Uncomment if you need to set a default value
        $claim->setUserId(0);
        $entityManager->persist($claim);
        $entityManager->flush();
        $this->addFlash('success', 'Claim submitted successfully!');
        return $this->redirectToRoute('avis_index');
    }

    // Handle search
    $avis = [];
    if ($searchForm->isSubmitted() && $searchForm->isValid()) {
        $searchData = $searchForm->getData();
        if (!empty($searchData['keyword'])) {
            $avis = $avisRepository->search($searchData['keyword']);
        } else {
            $avis = $avisRepository->findAll(); // fallback
        }
    } else {
        $avis = $avisRepository->findAll();
    }

    return $this->render('avis/index.html.twig', [
        'avis' => $avis,
        'avis_form' => $avisForm->createView(),
        'claims' => $claimRepository->findAll(),
        'claim_form' => $claimForm->createView(),
        'search_form' => $searchForm->createView(),
        'editing_claim' => null,
        'edit_claim_form' => null,
        'is_authenticated' => false, // Always false since we don't require auth
        'average_note' => $averageNote
    ]);
}

    #[Route('/{id}/edit', name: 'avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $avi = $entityManager->getRepository(Avis::class)->find($id);
        
        if (!$avi) {
            throw $this->createNotFoundException('Avis not found');
        }

        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Avis updated successfully!');
            return $this->redirectToRoute('avis_index');
        }

        return $this->render('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'avis_delete', methods: ['POST'])]
public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
{
    $avi = $entityManager->getRepository(Avis::class)->find($id);
    $referer = $request->headers->get('referer');
    $isAdmin = str_contains($referer, '/admin/');
    
    if (!$avi) {
        throw $this->createNotFoundException('Avis not found');
    }

    if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
        $entityManager->remove($avi);
        $entityManager->flush();
        $this->addFlash('success', 'Avis deleted successfully!');
    }

    return $this->redirect($isAdmin ? $this->generateUrl('admin_avis') : $this->generateUrl('avis_index'));
}
}