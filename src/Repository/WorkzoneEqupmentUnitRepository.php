<?php

namespace App\Repository;

use App\Entity\WorkzoneEqupmentUnit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkzoneEqupmentUnit>
 *
 * @method WorkzoneEqupmentUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkzoneEqupmentUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkzoneEqupmentUnit[]    findAll()
 * @method WorkzoneEqupmentUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkzoneEqupmentUnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkzoneEqupmentUnit::class);
    }

    public function add(WorkzoneEqupmentUnit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WorkzoneEqupmentUnit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return WorkzoneEqupmentUnit[] Returns an array of WorkzoneEqupmentUnit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WorkzoneEqupmentUnit
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
