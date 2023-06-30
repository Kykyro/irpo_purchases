<?php

namespace App\Repository;

use App\Entity\BuildingCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BuildingCategory>
 *
 * @method BuildingCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuildingCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuildingCategory[]    findAll()
 * @method BuildingCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuildingCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuildingCategory::class);
    }

    public function add(BuildingCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BuildingCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BuildingCategory[] Returns an array of BuildingCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BuildingCategory
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
