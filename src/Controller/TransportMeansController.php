<?php

namespace App\Controller;

use App\Entity\TransportMeans;
use App\Form\TransportMeansType;
use App\Repository\TransportMeansRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/transport-means')]
class TransportMeansController extends AbstractController
{
    #[Route('/', name: 'app_transport_means_index', methods: ['GET'])]
    public function index(Request $request, TransportMeansRepository $transportMeansRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $type = $request->query->get('type');
        $minCapacity = $request->query->get('minCapacity');
        $maxPrice = $request->query->get('maxPrice');

        if ($searchTerm || $type || $minCapacity || $maxPrice) {
            $transportMeans = $transportMeansRepository->search($searchTerm, $type, $minCapacity, $maxPrice);
        } else {
            $transportMeans = $transportMeansRepository->findAll();
        }

        return $this->render('transport_means/index.html.twig', [
            'transport_means' => $transportMeans,
            'searchTerm' => $searchTerm,
            'type' => $type,
            'minCapacity' => $minCapacity,
            'maxPrice' => $maxPrice,
        ]);
    }

    #[Route('/new', name: 'app_transport_means_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $transportMean = new TransportMeans();
        $form = $this->createForm(TransportMeansType::class, $transportMean);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('transport_means_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'There was an error uploading the image');
                }

                $transportMean->setImage($newFilename);
            }

            $entityManager->persist($transportMean);
            $entityManager->flush();

            $this->addFlash('success', 'Transport mean created successfully!');
            return $this->redirectToRoute('app_transport_means_index');
        }

        return $this->render('transport_means/new.html.twig', [
            'transport_mean' => $transportMean,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transport_means_show', methods: ['GET'])]
    public function show(TransportMeans $transportMean): Response
    {
        return $this->render('transport_means/show.html.twig', [
            'transport_mean' => $transportMean,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transport_means_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TransportMeans $transportMean, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TransportMeansType::class, $transportMean);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    // Delete old image if exists
                    if ($transportMean->getImage()) {
                        $oldImagePath = $this->getParameter('transport_means_images_directory').'/'.$transportMean->getImage();
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $imageFile->move(
                        $this->getParameter('transport_means_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'There was an error uploading the image');
                }

                $transportMean->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Transport mean updated successfully!');
            return $this->redirectToRoute('app_transport_means_index');
        }

        return $this->render('transport_means/edit.html.twig', [
            'transport_mean' => $transportMean,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transport_means_delete', methods: ['POST'])]
    public function delete(Request $request, TransportMeans $transportMean, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transportMean->getId(), $request->request->get('_token'))) {
            // Delete image file if exists
            if ($transportMean->getImage()) {
                $imagePath = $this->getParameter('transport_means_images_directory').'/'.$transportMean->getImage();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $entityManager->remove($transportMean);
            $entityManager->flush();

            $this->addFlash('success', 'Transport mean deleted successfully!');
        }

        return $this->redirectToRoute('app_transport_means_index');
    }
} 