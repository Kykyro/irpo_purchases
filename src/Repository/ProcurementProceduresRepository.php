<?php

namespace App\Repository;

use App\Entity\ProcurementProcedures;
use App\Entity\RfSubject;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProcurementProcedures>
 *
 * @method ProcurementProcedures|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProcurementProcedures|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProcurementProcedures[]    findAll()
 * @method ProcurementProcedures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcurementProceduresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProcurementProcedures::class);
    }

    public function add(ProcurementProcedures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProcurementProcedures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByYearAndRegion(int $year, RfSubject $rfSubject, $users) : array
    {

        $arr = [];
        foreach ($users as &$value) {
            $a = $this->createQueryBuilder('p')
                ->andWhere('p.user = :val')
                ->setParameter('val', $value->getId())
                ->orderBy('p.id', 'ASC')
                ->getQuery()
                ->getResult()
            ;
            array_push($arr, $a);
        }

        return $arr;
    }

//    /**
//     * @return ProcurementProcedures[] Returns an array of ProcurementProcedures objects
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

//    public function findOneBySomeField($value): ?ProcurementProcedures
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
