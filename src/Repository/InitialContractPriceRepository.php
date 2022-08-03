<?php

namespace App\Repository;

use App\Entity\InitialContractPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InitialContractPrice>
 *
 * @method InitialContractPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method InitialContractPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method InitialContractPrice[]    findAll()
 * @method InitialContractPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InitialContractPriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InitialContractPrice::class);
    }

    public function add(InitialContractPrice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InitialContractPrice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InitialContractPrice[] Returns an array of InitialContractPrice objects
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

//    public function findOneBySomeField($value): ?InitialContractPrice
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
