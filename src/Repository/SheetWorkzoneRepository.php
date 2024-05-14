<?php

namespace App\Repository;

use App\Entity\SheetWorkzone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SheetWorkzone>
 *
 * @method SheetWorkzone|null find($id, $lockMode = null, $lockVersion = null)
 * @method SheetWorkzone|null findOneBy(array $criteria, array $orderBy = null)
 * @method SheetWorkzone[]    findAll()
 * @method SheetWorkzone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SheetWorkzoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SheetWorkzone::class);
    }

    public function add(SheetWorkzone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SheetWorkzone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SheetWorkzone[] Returns an array of SheetWorkzone objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SheetWorkzone
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
