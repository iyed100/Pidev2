<?php

namespace App\Controller;

use App\Entity\CoworkingSpace;
use App\Form\CoworkingSpaceType;
use App\Repository\CoworkingSpaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/coworking-space')]
class CoworkingSpaceController extends AbstractController
{
    #[Route('/', name: 'app_coworking_space_index', methods: ['GET'])]
    public function index(CoworkingSpaceRepository $coworkingSpaceRepository): Response
    {
        return $this->render('coworking_space/index.html.twig', [
            'coworking_spaces' => $coworkingSpaceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_coworking_space_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coworkingSpace = new CoworkingSpace();
        $form = $this->createForm(CoworkingSpaceType::class, $coworkingSpace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coworkingSpace);
            $entityManager->flush();

            return $this->redirectToRoute('app_coworking_space_index');
        }

        return $this->render('coworking_space/new.html.twig', [
            'coworking_space' => $coworkingSpace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coworking_space_show', methods: ['GET'])]
    public function show(CoworkingSpace $coworkingSpace): Response
    {
        return $this->render('coworking_space/show.html.twig', [
            'coworking_space' => $coworkingSpace,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coworking_space_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CoworkingSpace $coworkingSpace, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoworkingSpaceType::class, $coworkingSpace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coworking_space_index');
        }

        return $this->render('coworking_space/edit.html.twig', [
            'coworking_space' => $coworkingSpace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coworking_space_delete', methods: ['POST'])]
    public function delete(Request $request, CoworkingSpace $coworkingSpace, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coworkingSpace->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coworkingSpace);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coworking_space_index');
    }
} 