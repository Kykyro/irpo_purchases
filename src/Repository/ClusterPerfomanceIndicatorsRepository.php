<?php

namespace App\Repository;

use App\Entity\ClusterPerfomanceIndicators;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClusterPerfomanceIndicators>
 *
 * @method ClusterPerfomanceIndicators|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClusterPerfomanceIndicators|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClusterPerfomanceIndicators[]    findAll()
 * @method ClusterPerfomanceIndicators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClusterPerfomanceIndicatorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClusterPerfomanceIndicators::class);
    }

    public function add(ClusterPerfomanceIndicators $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClusterPerfomanceIndicators $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ClusterPerfomanceIndicators[] Returns an array of ClusterPerfomanceIndicators objects
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

//    public function findOneBySomeField($value): ?ClusterPerfomanceIndicators
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
