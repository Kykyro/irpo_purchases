<?php

namespace App\Repository;

use App\Entity\UAVsTypeEquipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UAVsTypeEquipment>
 *
 * @method UAVsTypeEquipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method UAVsTypeEquipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method UAVsTypeEquipment[]    findAll()
 * @method UAVsTypeEquipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UAVsTypeEquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UAVsTypeEquipment::class);
    }

    public function add(UAVsTypeEquipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UAVsTypeEquipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UAVsTypeEquipment[] Returns an array of UAVsTypeEquipment objects
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

//    public function findOneBySomeField($value): ?UAVsTypeEquipment
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
