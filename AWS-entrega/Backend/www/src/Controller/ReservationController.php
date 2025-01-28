<?php

namespace App\Controller;

use App\Entity\Apartment; 
use App\Entity\Reservation; 
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractController
{
    private $entityManager; 
    
    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/reservations', name: 'create_reservation', methods: ['POST'])]
    public function createReservation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        if (!isset($data['apartment_id'], $data['start_date'], $data['end_date'], $data['reservation_contact'])) {
            return $this->json(['message' => 'All fields are required'], Response::HTTP_BAD_REQUEST);
        }
        
        $apartment = $this->entityManager->getRepository(Apartment::class)->find($data['apartment_id']);
    
        if (!$apartment) {
            return $this->json(['message' => 'The apartment does not exist'], Response::HTTP_NOT_FOUND);
        }
    
        $startDate = new \DateTime($data['start_date']);
        $endDate = new \DateTime($data['end_date']);
    
        $reservations = $this->entityManager->getRepository(Reservation::class)->findBy(['apartment' => $apartment]);
    
        foreach ($reservations as $reservation) {
            if ($reservation->getStartDate() < $endDate && $startDate < $reservation->getEndDate()) {
                return $this->json(['message' => 'The apartment is already reserved on these dates'], Response::HTTP_CONFLICT);
            }
        }
    
        $reservation = new Reservation();
        $reservation->setApartment($apartment);
        $reservation->setStartDate($startDate);
        $reservation->setEndDate($endDate);
        $reservation->setCancelled($data['cancelled']);
        if ($data['cancellation_date']) {
            $reservation->setCancellationDate(new \DateTime($data['cancellation_date']));
        }
        $reservation->setReservationContact($data['reservation_contact']);

        $this->entityManager->persist($reservation);
        $today = new \DateTime();
        if ($startDate > $today && $endDate > $today) {
            $apartment->setOccupied(true);
            $this->entityManager->persist($apartment);
        }
        $this->entityManager->flush();
    
        return $this->json(['message' => 'Reservation created successfully'], Response::HTTP_CREATED);
    }
    

    #[Route('/reservations/{id}', name: 'cancel_reservation', methods: ['PATCH'])]
    public function cancelReservation(int $id): JsonResponse
    {
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            return $this->json(['message' => 'The reservation does not exist'], Response::HTTP_NOT_FOUND);
        }

        if ($reservation->isCancelled()) {
            return $this->json(['message' => 'The reservation is already cancelled'], Response::HTTP_CONFLICT);
        }

        $reservation->setCancelled(true);
        $reservation->setCancellationDate(new \DateTime());
        $today = new \DateTime();
        if ($reservation->getEndDate() > $today) {
            $apartment = $reservation->getApartment();
            $apartment->setOccupied(false);
            $this->entityManager->persist($apartment);
}
        $this->entityManager->flush();

        return $this->json(['message' => 'Reservation cancelled successfully'], Response::HTTP_OK);
    }

}
