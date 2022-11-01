<?php

namespace App\Repository;

use App\Entity\InfrastructureSheetRegionFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfrastructureSheetRegionFile>
 *
 * @method InfrastructureSheetRegionFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfrastructureSheetRegionFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfrastructureSheetRegionFile[]    findAll()
 * @method InfrastructureSheetRegionFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfrastructureSheetRegionFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfrastructureSheetRegionFile::class);
    }

    public function add(InfrastructureSheetRegionFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InfrastructureSheetRegionFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InfrastructureSheetRegionFile[] Returns an array of InfrastructureSheetRegionFile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InfrastructureSheetRegionFile
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
