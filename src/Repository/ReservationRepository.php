<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Trouve toutes les réservations d'un utilisateur
     */
    public function countByHotel(): array
    {
        $results = $this->createQueryBuilder('r')
            ->select('h.nom as hotel_name, COUNT(r.id) as count')
            ->leftJoin('r.hotel', 'h')
            ->groupBy('h.id')
            ->getQuery()
            ->getResult();

        // Formatte les résultats
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'hotel_name' => $result['hotel_name'] ?? 'Hôtel inconnu',
                'count' => (int)$result['count']
            ];
        }

        // Debug temporaire
        dump($formattedResults);

        return $formattedResults;
    }
    public function findByUser(Utilisateur $user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.utilisateur = :user')
            ->setParameter('user', $user)
            ->leftJoin('r.hotel', 'h')
            ->leftJoin('r.coworkingSpace', 'c')
            ->leftJoin('r.transportMean', 't')
            ->addSelect(['h', 'c', 't'])
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Pour l'export PDF des réservations d'un utilisateur spécifique
     */
    public function findUserReservationsForPdf(Utilisateur $user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.utilisateur = :user')
            ->setParameter('user', $user)
            ->leftJoin('r.hotel', 'h')
            ->leftJoin('r.coworkingSpace', 'c')
            ->leftJoin('r.transportMean', 't')
            ->addSelect(['h', 'c', 't'])
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // On garde l'ancienne méthode au cas où (pour l'admin par exemple)
    public function findAllForPdfExport()
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.hotel', 'h')
            ->leftJoin('r.coworkingSpace', 'c')
            ->leftJoin('r.transportMean', 't')
            ->addSelect(['h', 'c', 't'])
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}