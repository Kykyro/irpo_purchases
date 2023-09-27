<?php

namespace App\Repository;

use App\Entity\MonitoringCheckOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MonitoringCheckOut>
 *
 * @method MonitoringCheckOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method MonitoringCheckOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method MonitoringCheckOut[]    findAll()
 * @method MonitoringCheckOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonitoringCheckOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonitoringCheckOut::class);
    }

    public function add(MonitoringCheckOut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MonitoringCheckOut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MonitoringCheckOut[] Returns an array of MonitoringCheckOut objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MonitoringCheckOut
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
