<?php

namespace App\Repository;

use App\Entity\ReadinessMapArchive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReadinessMapArchive>
 *
 * @method ReadinessMapArchive|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadinessMapArchive|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadinessMapArchive[]    findAll()
 * @method ReadinessMapArchive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadinessMapArchiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadinessMapArchive::class);
    }

    public function add(ReadinessMapArchive $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReadinessMapArchive $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ReadinessMapArchive[] Returns an array of ReadinessMapArchive objects
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

//    public function findOneBySomeField($value): ?ReadinessMapArchive
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
