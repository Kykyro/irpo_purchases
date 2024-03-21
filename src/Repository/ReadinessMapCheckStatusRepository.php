<?php

namespace App\Repository;

use App\Entity\ReadinessMapCheckStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReadinessMapCheckStatus>
 *
 * @method ReadinessMapCheckStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadinessMapCheckStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadinessMapCheckStatus[]    findAll()
 * @method ReadinessMapCheckStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadinessMapCheckStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadinessMapCheckStatus::class);
    }

    public function add(ReadinessMapCheckStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReadinessMapCheckStatus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ReadinessMapCheckStatus[] Returns an array of ReadinessMapCheckStatus objects
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

//    public function findOneBySomeField($value): ?ReadinessMapCheckStatus
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
