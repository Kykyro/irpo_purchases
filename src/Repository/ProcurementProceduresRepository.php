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

        $startDate = new \DateTimeImmutable("$year-01-01T00:00:00");
        $endDate =  new \DateTimeImmutable("$year-12-31T23:59:59");
        $arr = [];
        foreach ($users as &$value) {
            $a = $this->createQueryBuilder('p')
                ->andWhere('p.MethodOfDetermining != :method')
                ->andWhere('p.publicationDate BETWEEN :start AND :end')
                ->orWhere('p.MethodOfDetermining = :method  AND p.DateOfConclusion BETWEEN :start AND :end')
                ->andWhere('p.isDeleted = FALSE')
                ->andWhere('p.user = :val')
                ->setParameter('val', $value)
                ->setParameter('method', 'Единственный поставщик')
                ->setParameter('start', $startDate->format('Y-m-d H:i:s'))
                ->setParameter('end', $endDate->format('Y-m-d H:i:s'))
                ->orderBy('p.id', 'ASC')
                ->getQuery()
                ->getResult()
            ;

            array_push($arr, $a);
        }

        return $arr;
    }
    public function findByUserAndYear(User $user, int $year) : array
    {
        $startDate = new \DateTimeImmutable("$year-01-01T00:00:00");
        $endDate =  new \DateTimeImmutable("$year-12-31T23:59:59");

        $arr = $this->createQueryBuilder('p')
            ->andWhere('p.MethodOfDetermining != :method')
            ->andWhere('p.publicationDate BETWEEN :start AND :end')
            ->orWhere('p.MethodOfDetermining = :method  AND p.DateOfConclusion BETWEEN :start AND :end')
            ->andWhere('p.isDeleted = FALSE')
            ->andWhere('p.user = :val')
            ->setParameter('val', $user)
            ->setParameter('method', 'Единственный поставщик')
            ->setParameter('start', $startDate->format('Y-m-d H:i:s'))
            ->setParameter('end', $endDate->format('Y-m-d H:i:s'))
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;

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
