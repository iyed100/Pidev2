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
    public function countByType(): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.type, COUNT(a.id) as count')
            ->groupBy('a.type')
            ->getQuery()
            ->getResult();
    
        // Formatte les résultats pour être sûr de la structure
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'type' => $result['type'],
                'count' => (int)$result['count']
            ];
        }
    
        // Debug temporaire
        dump($formattedResults);
    
        return $formattedResults;
    }
    public function countByStatus(): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.statut as status, COUNT(a.id) as count')
            ->groupBy('a.statut')
            ->getQuery()
            ->getResult();

        // Formatte les résultats
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'status' => $result['status'] ?? 'Inconnu',
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