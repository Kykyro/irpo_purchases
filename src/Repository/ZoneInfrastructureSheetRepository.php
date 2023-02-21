<?php

namespace App\Repository;

use App\Entity\ZoneInfrastructureSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ZoneInfrastructureSheet>
 *
 * @method ZoneInfrastructureSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZoneInfrastructureSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZoneInfrastructureSheet[]    findAll()
 * @method ZoneInfrastructureSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneInfrastructureSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneInfrastructureSheet::class);
    }

    public function add(ZoneInfrastructureSheet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ZoneInfrastructureSheet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ZoneInfrastructureSheet[] Returns an array of ZoneInfrastructureSheet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('z')
//            ->andWhere('z.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('z.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ZoneInfrastructureSheet
//    {
//        return $this->createQueryBuilder('z')
//            ->andWhere('z.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
