<?php

namespace App\Repository;

use App\Entity\WorkzoneEquipmentDump;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkzoneEquipmentDump>
 *
 * @method WorkzoneEquipmentDump|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkzoneEquipmentDump|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkzoneEquipmentDump[]    findAll()
 * @method WorkzoneEquipmentDump[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkzoneEquipmentDumpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkzoneEquipmentDump::class);
    }

    public function add(WorkzoneEquipmentDump $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WorkzoneEquipmentDump $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return WorkzoneEquipmentDump[] Returns an array of WorkzoneEquipmentDump objects
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

//    public function findOneBySomeField($value): ?WorkzoneEquipmentDump
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
