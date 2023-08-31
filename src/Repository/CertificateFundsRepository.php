<?php

namespace App\Repository;

use App\Entity\CertificateFunds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CertificateFunds>
 *
 * @method CertificateFunds|null find($id, $lockMode = null, $lockVersion = null)
 * @method CertificateFunds|null findOneBy(array $criteria, array $orderBy = null)
 * @method CertificateFunds[]    findAll()
 * @method CertificateFunds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificateFundsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CertificateFunds::class);
    }

    public function add(CertificateFunds $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CertificateFunds $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CertificateFunds[] Returns an array of CertificateFunds objects
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

//    public function findOneBySomeField($value): ?CertificateFunds
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
