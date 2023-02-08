<?php

namespace App\Repository;

use App\Entity\PurchasesDumpData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PurchasesDumpData>
 *
 * @method PurchasesDumpData|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchasesDumpData|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchasesDumpData[]    findAll()
 * @method PurchasesDumpData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchasesDumpDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchasesDumpData::class);
    }

    public function add(PurchasesDumpData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PurchasesDumpData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PurchasesDumpData[] Returns an array of PurchasesDumpData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PurchasesDumpData
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
