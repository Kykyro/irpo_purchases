<?php

namespace App\Repository;

use App\Entity\ReadinessMapSaves;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReadinessMapSaves>
 *
 * @method ReadinessMapSaves|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadinessMapSaves|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadinessMapSaves[]    findAll()
 * @method ReadinessMapSaves[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadinessMapSavesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReadinessMapSaves::class);
    }

    public function add(ReadinessMapSaves $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReadinessMapSaves $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ReadinessMapSaves[] Returns an array of ReadinessMapSaves objects
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

//    public function findOneBySomeField($value): ?ReadinessMapSaves
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
