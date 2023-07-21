<?php

namespace App\Repository;

use App\Entity\ClustersRequestCertification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClustersRequestCertification>
 *
 * @method ClustersRequestCertification|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClustersRequestCertification|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClustersRequestCertification[]    findAll()
 * @method ClustersRequestCertification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClustersRequestCertificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClustersRequestCertification::class);
    }

    public function add(ClustersRequestCertification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClustersRequestCertification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ClustersRequestCertification[] Returns an array of ClustersRequestCertification objects
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

//    public function findOneBySomeField($value): ?ClustersRequestCertification
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
