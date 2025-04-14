<?php

namespace App\Repository;

use App\Entity\CoworkingSpace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoworkingSpace>
 */
class CoworkingSpaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoworkingSpace::class);
    }

    public function save(CoworkingSpace $coworkingSpace, bool $flush = false): void
    {
        $this->getEntityManager()->persist($coworkingSpace);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CoworkingSpace $coworkingSpace, bool $flush = false): void
    {
        $this->getEntityManager()->remove($coworkingSpace);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearchCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('c');

        if (isset($criteria['nom'])) {
            $qb->andWhere('c.nom LIKE :nom')
               ->setParameter('nom', '%' . $criteria['nom'] . '%');
        }

        if (isset($criteria['prixMin'])) {
            $qb->andWhere('c.prixParHeure >= :prixMin')
               ->setParameter('prixMin', $criteria['prixMin']);
        }

        if (isset($criteria['prixMax'])) {
            $qb->andWhere('c.prixParHeure <= :prixMax')
               ->setParameter('prixMax', $criteria['prixMax']);
        }

        if (isset($criteria['hotel'])) {
            $qb->andWhere('c.hotel = :hotel')
               ->setParameter('hotel', $criteria['hotel']);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return CoworkingSpace[] Returns an array of CoworkingSpace objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CoworkingSpace
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
