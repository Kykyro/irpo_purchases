<?php

namespace App\Repository;

use App\Entity\ReadinessMapCheck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReadinessMapCheck>
 *
 * @method ReadinessMapCheck|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadinessMapCheck|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadinessMapCheck[]    findAll()
 * @method ReadinessMapCheck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadinessMapCheckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadinessMapCheck::class);
    }

    public function add(ReadinessMapCheck $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReadinessMapCheck $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ReadinessMapCheck[] Returns an array of ReadinessMapCheck objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReadinessMapCheck
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
