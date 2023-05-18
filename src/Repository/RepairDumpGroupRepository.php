<?php

namespace App\Repository;

use App\Entity\RepairDumpGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RepairDumpGroup>
 *
 * @method RepairDumpGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepairDumpGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepairDumpGroup[]    findAll()
 * @method RepairDumpGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepairDumpGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepairDumpGroup::class);
    }

    public function add(RepairDumpGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RepairDumpGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RepairDumpGroup[] Returns an array of RepairDumpGroup objects
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

//    public function findOneBySomeField($value): ?RepairDumpGroup
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
