<?php

namespace App\Repository;

use App\Entity\CofinancingFunds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CofinancingFunds>
 *
 * @method CofinancingFunds|null find($id, $lockMode = null, $lockVersion = null)
 * @method CofinancingFunds|null findOneBy(array $criteria, array $orderBy = null)
 * @method CofinancingFunds[]    findAll()
 * @method CofinancingFunds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CofinancingFundsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CofinancingFunds::class);
    }

    public function add(CofinancingFunds $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CofinancingFunds $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CofinancingFunds[] Returns an array of CofinancingFunds objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CofinancingFunds
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
