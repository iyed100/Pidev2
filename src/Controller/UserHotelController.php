<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserHotelController extends AbstractController
{
    #[Route('/hotels', name: 'app_user_hotels')]
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('hotel/user_index.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    #[Route('/hotel/{id}', name: 'app_user_hotel_show')]
    public function show(int $id, HotelRepository $hotelRepository): Response
    {
        $hotel = $hotelRepository->find($id);
        
        if (!$hotel) {
            throw $this->createNotFoundException('Hôtel non trouvé');
        }
        
        return $this->render('hotel/user_show.html.twig', [
            'hotel' => $hotel,
        ]);
    }
} 