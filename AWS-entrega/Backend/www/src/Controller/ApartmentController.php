<?php

namespace App\Controller;

use App\Repository\ApartmentRepository;
use App\Repository\PhotoRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApartmentController extends AbstractController
{

    #[Route('/apartments', name: 'get_apartment', methods: ['GET'])]
    public function getApartments(ApartmentRepository $apartmentRepository, PhotoRepository $photoRepository, ReservationRepository $reservationRepository): JsonResponse
    {
        $apartments = $apartmentRepository->findAll();
    
        $data = array_map(function ($apartment) use ($photoRepository, $reservationRepository) {
            $photos = $photoRepository->findBy(['apartment' => $apartment]);
    
            $photoData = array_map(function ($photo) {
                return [
                    'id' => $photo->getId(),
                    'url' => $photo->getUrl(),
                ];
            }, $photos);
    
            $reservations = $reservationRepository->findBy(['apartment' => $apartment]);
    
            $isOccupied = false;
            $occupiedDates = null;
            foreach ($reservations as $reservation) {
                if ($reservation->getStartDate() <= new \DateTime() && $reservation->getEndDate() >= new \DateTime()) {
                    $apartment->setOccupied(true);
                    $isOccupied = true;
                    $occupiedDates = [
                        'start_date' => $reservation->getStartDate()->format('Y-m-d H:i:s'),
                        'end_date' => $reservation->getEndDate()->format('Y-m-d H:i:s'),
                    ];
                    break;
                }
            }
    
            return [
                'id' => $apartment->getId(),
                'title' => $apartment->getTitle(),
                'description' => $apartment->getDescription(),
                'direction' => $apartment->getDirection(),
                'price' => $apartment->getPrice(),
                'occupied' => $isOccupied,
                'occupied_dates' => $occupiedDates,
                'photos' => $photoData,
            ];
        }, $apartments);
    
        return $this->json($data);
    }

}
