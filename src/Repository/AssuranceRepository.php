<?php

namespace App\Repository;

use App\Entity\Assurance;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AssuranceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assurance::class);
    }
    // Ajoute cette méthode dans AssuranceRepository.php
    public function countByStatus(): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.statut as status, COUNT(a.id) as count')
            ->groupBy('a.statut')
            ->getQuery()
            ->getResult();

        // Liste des statuts possibles
        $possibleStatuses = ['Actif', 'Inactif', 'En attente'];
        
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
     * Compte le nombre d'assurances par type
     */
    public function countByType(): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.type, COUNT(a.id) as count')
            ->groupBy('a.type')
            ->getQuery()
            ->getResult();
    
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'type' => $result['type'],
                'count' => (int)$result['count']
            ];
        }
    
        return $formattedResults;
    }

    /**
     * Compte le nombre d'assurances par client (utilisateur)
     */
    public function countByClient(): array
    {
        $results = $this->createQueryBuilder('a')
            ->select("CONCAT(u.nom, ' ', u.prenom) as client_name, COUNT(a.id) as count")
            ->join('a.reservation', 'r')
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
     * Trouve toutes les assurances d'un utilisateur
     */
    public function findByUser(Utilisateur $user)
    {
        return $this->createQueryBuilder('a')
            ->join('a.reservation', 'r')
            ->andWhere('r.utilisateur = :user')
            ->setParameter('user', $user)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}