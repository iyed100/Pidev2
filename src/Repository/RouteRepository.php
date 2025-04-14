<?php

namespace App\Repository;

use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Route>
 */
class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Route::class);
    }

    public function save(Route $route, bool $flush = false): void
    {
        $this->getEntityManager()->persist($route);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Route $route, bool $flush = false): void
    {
        $this->getEntityManager()->remove($route);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearchCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('r');

        if (isset($criteria['depart'])) {
            $qb->andWhere('r.depart LIKE :depart')
               ->setParameter('depart', '%' . $criteria['depart'] . '%');
        }

        if (isset($criteria['arrivee'])) {
            $qb->andWhere('r.arrivee LIKE :arrivee')
               ->setParameter('arrivee', '%' . $criteria['arrivee'] . '%');
        }

        if (isset($criteria['distanceMin'])) {
            $qb->andWhere('r.distance >= :distanceMin')
               ->setParameter('distanceMin', $criteria['distanceMin']);
        }

        if (isset($criteria['distanceMax'])) {
            $qb->andWhere('r.distance <= :distanceMax')
               ->setParameter('distanceMax', $criteria['distanceMax']);
        }

        if (isset($criteria['transport'])) {
            $qb->andWhere('r.transport = :transport')
               ->setParameter('transport', $criteria['transport']);
        }

        return $qb->getQuery()->getResult();
    }
} 