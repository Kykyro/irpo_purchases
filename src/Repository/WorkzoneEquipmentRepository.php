<?php

namespace App\Repository;

use App\Entity\WorkzoneEquipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkzoneEquipment>
 *
 * @method WorkzoneEquipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkzoneEquipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkzoneEquipment[]    findAll()
 * @method WorkzoneEquipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkzoneEquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkzoneEquipment::class);
    }

    public function add(WorkzoneEquipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WorkzoneEquipment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return WorkzoneEquipment[] Returns an array of WorkzoneEquipment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WorkzoneEquipment
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
