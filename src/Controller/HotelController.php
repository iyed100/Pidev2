<?php

namespace App\Controller;
use App\Service\FileUploader;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hotel')]
final class HotelController extends AbstractController
{
    #[Route(name: 'app_hotel_index', methods: ['GET'])]
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/index.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_hotel_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ): Response {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                $fileName = $fileUploader->upload($imageFile);
                $hotel->setImage($fileName);  // Sauvegarde le nom du fichier dans l'entité
            }
    
            $entityManager->persist($hotel);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_hotel_index');
        }
    
        return $this->render('hotel/new.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_hotel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hotel $hotel, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $hotel->setImage($imageFileName);
            }
    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_hotel_show', methods: ['GET'])]
    public function show(Hotel $hotel): Response
    {
        return $this->render('hotel/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

   

    #[Route('/{id}', name: 'app_hotel_delete', methods: ['POST'])]
    public function delete(Request $request, Hotel $hotel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hotel->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($hotel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hotel_index', [], Response::HTTP_SEE_OTHER);
    }
}
