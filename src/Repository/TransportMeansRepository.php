<?php

namespace App\Repository;

use App\Entity\TransportMeans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TransportMeans>
 *
 * @method TransportMeans|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransportMeans|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransportMeans[]    findAll()
 * @method TransportMeans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportMeansRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransportMeans::class);
    }

    public function save(TransportMeans $transport, bool $flush = false): void
    {
        $this->getEntityManager()->persist($transport);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TransportMeans $transport, bool $flush = false): void
    {
        $this->getEntityManager()->remove($transport);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearchCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('t');

        if (isset($criteria['nom'])) {
            $qb->andWhere('t.nom LIKE :nom')
               ->setParameter('nom', '%' . $criteria['nom'] . '%');
        }

        if (isset($criteria['type'])) {
            $qb->andWhere('t.type = :type')
               ->setParameter('type', $criteria['type']);
        }

        if (isset($criteria['prixMin'])) {
            $qb->andWhere('t.prix >= :prixMin')
               ->setParameter('prixMin', $criteria['prixMin']);
        }

        if (isset($criteria['prixMax'])) {
            $qb->andWhere('t.prix <= :prixMax')
               ->setParameter('prixMax', $criteria['prixMax']);
        }

        if (isset($criteria['dateDepart'])) {
            $qb->andWhere('t.dateDepart = :dateDepart')
               ->setParameter('dateDepart', $criteria['dateDepart']);
        }

        return $qb->getQuery()->getResult();
    }

    public function search(?string $searchTerm = null, ?string $type = null, ?int $minCapacity = null, ?float $maxPrice = null): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($searchTerm) {
            $qb->andWhere('t.nom LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        if ($type) {
            $qb->andWhere('t.type = :type')
               ->setParameter('type', $type);
        }

        if ($minCapacity) {
            $qb->andWhere('t.capacite >= :minCapacity')
               ->setParameter('minCapacity', $minCapacity);
        }

        if ($maxPrice) {
            $qb->andWhere('t.prix <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        return $qb->orderBy('t.nom', 'ASC')
                 ->getQuery()
                 ->getResult();
    }

    //    /**
    //     * @return TransportMeans[] Returns an array of TransportMeans objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TransportMeans
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
