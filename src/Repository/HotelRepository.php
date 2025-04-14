<?php

namespace App\Repository;

use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hotel>
 *
 * @method Hotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hotel[]    findAll()
 * @method Hotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    public function save(Hotel $hotel, bool $flush = false): void
    {
        $this->getEntityManager()->persist($hotel);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hotel $hotel, bool $flush = false): void
    {
        $this->getEntityManager()->remove($hotel);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearchCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('h');

        if (isset($criteria['nom'])) {
            $qb->andWhere('h.nom LIKE :nom')
               ->setParameter('nom', '%' . $criteria['nom'] . '%');
        }

        if (isset($criteria['nombreEtoiles'])) {
            $qb->andWhere('h.nombreEtoiles = :nombreEtoiles')
               ->setParameter('nombreEtoiles', $criteria['nombreEtoiles']);
        }

        if (isset($criteria['prixMin'])) {
            $qb->andWhere('h.prixParNuit >= :prixMin')
               ->setParameter('prixMin', $criteria['prixMin']);
        }

        if (isset($criteria['prixMax'])) {
            $qb->andWhere('h.prixParNuit <= :prixMax')
               ->setParameter('prixMax', $criteria['prixMax']);
        }

        return $qb->getQuery()->getResult();
    }

    public function search(?string $searchTerm = null, ?int $minStars = null, ?float $maxPrice = null): array
    {
        $qb = $this->createQueryBuilder('h');

        if ($searchTerm) {
            $qb->andWhere('h.nom LIKE :searchTerm OR h.adresse LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        if ($minStars) {
            $qb->andWhere('h.nombreEtoiles >= :minStars')
               ->setParameter('minStars', $minStars);
        }

        if ($maxPrice) {
            $qb->andWhere('h.prixParNuit <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        return $qb->orderBy('h.nom', 'ASC')
                 ->getQuery()
                 ->getResult();
    }

    //    /**
    //     * @return Hotel[] Returns an array of Hotel objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Hotel
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
