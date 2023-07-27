<?php

namespace App\Repository;

use App\Entity\EmployersContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmployersContact>
 *
 * @method EmployersContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployersContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployersContact[]    findAll()
 * @method EmployersContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployersContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployersContact::class);
    }

    public function add(EmployersContact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EmployersContact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EmployersContact[] Returns an array of EmployersContact objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EmployersContact
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
