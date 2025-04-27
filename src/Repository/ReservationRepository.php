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
     * Trouve toutes les réservations avec leurs relations pour affichage
     */
    public function findAllWithDetails(?Utilisateur $user = null)
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.hotel', 'h')
            ->leftJoin('r.coworkingSpace', 'cs')
            ->leftJoin('r.transportMean', 'tm')
            ->addSelect('h')
            ->addSelect('cs')
            ->addSelect('tm');

        if ($user && $user->getRole() !== 'admin') {
            $qb->andWhere('r.utilisateur = :user')
               ->setParameter('user', $user);
        }

        return $qb->orderBy('r.id', 'DESC')
                  ->getQuery()
                  ->getResult();
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

    public function countByStatus(): array
    {
        $results = $this->createQueryBuilder('r')
            ->select('r.statut as status, COUNT(r.id) as count')
            ->groupBy('r.statut')
            ->getQuery()
            ->getResult();

        // Liste des statuts possibles
        $possibleStatuses = ['Confirmé', 'En attente'];

        // Initialise le tableau des résultats avec 0 pour chaque statut
        $formattedResults = [];
        foreach ($possibleStatuses as $status) {
            $formattedResults[$status] = [
                'status' => $status,
                'count' => 0
            ];
        }

        // Remplit les comptes réels à partir des résultats
        foreach ($results as $result) {
            $status = $result['status'] ?? 'Inconnu';
            if (in_array($status, $possibleStatuses)) {
                $formattedResults[$status]['count'] = (int)$result['count'];
            }
        }

        // Convertit en tableau indexé pour Twig
        $formattedResults = array_values($formattedResults);

        // Debug temporaire
        dump($formattedResults);

        return $formattedResults;
    }

    /**
     * Compte le nombre de réservations par client (utilisateur)
     */
    public function countByClient(): array
    {
        $results = $this->createQueryBuilder('r')
            ->select("CONCAT(u.nom, ' ', u.prenom) as client_name, COUNT(r.id) as count")
            ->join('r.utilisateur', 'u')
            ->groupBy('u.id, u.nom, u.prenom')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getResult();

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'client_name' => $result['client_name'] ?? 'Client inconnu',
                'count' => (int)$result['count']
            ];
        }

        // Debug temporaire
        dump($formattedResults);

        return $formattedResults;
    }

    /**
     * Compte le nombre de réservations par espace de coworking
     */
    public function countByCoworking(): array
    {
        $results = $this->createQueryBuilder('r')
            ->select('cs.nom as coworking_name, COUNT(r.id) as count')
            ->leftJoin('r.coworkingSpace', 'cs')
            ->where('r.coworkingSpace IS NOT NULL')
            ->groupBy('cs.nom')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getResult();

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'coworking_name' => $result['coworking_name'] ?? 'Espace inconnu',
                'count' => (int)$result['count']
            ];
        }

        // Debug temporaire
        dump($formattedResults);

        return $formattedResults;
    }

    /**
     * Compte le nombre de réservations par transport
     */
    public function countByTransport(): array
    {
        $results = $this->createQueryBuilder('r')
            ->select('tm.nom as transport_name, COUNT(r.id) as count')
            ->leftJoin('r.transportMean', 'tm')
            ->where('r.transportMean IS NOT NULL')
            ->groupBy('tm.nom')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getResult();

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'transport_name' => $result['transport_name'] ?? 'Transport inconnu',
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