<?php

namespace App\Repository;

use App\Entity\UAVsCertificate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UAVsCertificate>
 *
 * @method UAVsCertificate|null find($id, $lockMode = null, $lockVersion = null)
 * @method UAVsCertificate|null findOneBy(array $criteria, array $orderBy = null)
 * @method UAVsCertificate[]    findAll()
 * @method UAVsCertificate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UAVsCertificateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UAVsCertificate::class);
    }

    public function add(UAVsCertificate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UAVsCertificate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UAVsCertificate[] Returns an array of UAVsCertificate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UAVsCertificate
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
