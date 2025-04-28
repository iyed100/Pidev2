<?php

namespace App\Controller;

use App\Entity\CoworkingSpace;
use App\Form\CoworkingSpaceType;
use App\Repository\CoworkingSpaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/coworking-space')]
class AdminCoworkingSpaceController extends AbstractController
{
    #[Route('/', name: 'admin_coworking_space_index', methods: ['GET'])]
    public function index(CoworkingSpaceRepository $coworkingSpaceRepository): Response
    {
        return $this->render('coworking_space/admin_index.html.twig', [
            'coworking_spaces' => $coworkingSpaceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_coworking_space_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $coworkingSpace = new CoworkingSpace();
        $form = $this->createForm(CoworkingSpaceType::class, $coworkingSpace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('coworking_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors du téléchargement de l\'image.');
                }

                $coworkingSpace->setImage($newFilename);
            }

            $entityManager->persist($coworkingSpace);
            $entityManager->flush();

            $this->addFlash('success', 'L\'espace de coworking a été créé avec succès.');
            return $this->redirectToRoute('admin_coworking_space_index');
        }

        return $this->render('coworking_space/new.html.twig', [
            'coworking_space' => $coworkingSpace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_coworking_space_show', methods: ['GET'])]
    public function show(CoworkingSpace $coworkingSpace): Response
    {
        return $this->render('coworking_space/admin_show.html.twig', [
            'coworking_space' => $coworkingSpace,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_coworking_space_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CoworkingSpace $coworkingSpace, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CoworkingSpaceType::class, $coworkingSpace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('coworking_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors du téléchargement de l\'image.');
                }

                $coworkingSpace->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'L\'espace de coworking a été modifié avec succès.');
            return $this->redirectToRoute('admin_coworking_space_index');
        }

        return $this->render('coworking_space/edit.html.twig', [
            'coworking_space' => $coworkingSpace,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_coworking_space_delete', methods: ['POST'])]
    public function delete(Request $request, CoworkingSpace $coworkingSpace, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coworkingSpace->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coworkingSpace);
            $entityManager->flush();
            $this->addFlash('success', 'L\'espace de coworking a été supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_coworking_space_index');
    }
} 