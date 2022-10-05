<?php

namespace App\Repository;

use App\Entity\DesignProjectExample;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DesignProjectExample>
 *
 * @method DesignProjectExample|null find($id, $lockMode = null, $lockVersion = null)
 * @method DesignProjectExample|null findOneBy(array $criteria, array $orderBy = null)
 * @method DesignProjectExample[]    findAll()
 * @method DesignProjectExample[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DesignProjectExampleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DesignProjectExample::class);
    }

    public function add(DesignProjectExample $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DesignProjectExample $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DesignProjectExample[] Returns an array of DesignProjectExample objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DesignProjectExample
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
