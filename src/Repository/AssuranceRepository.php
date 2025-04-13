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