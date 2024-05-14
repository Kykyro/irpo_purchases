<?php

namespace App\Repository;

use App\Entity\ZoneRequirements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ZoneRequirements>
 *
 * @method ZoneRequirements|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZoneRequirements|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZoneRequirements[]    findAll()
 * @method ZoneRequirements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneRequirementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneRequirements::class);
    }

    public function add(ZoneRequirements $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ZoneRequirements $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ZoneRequirements[] Returns an array of ZoneRequirements objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('z')
//            ->andWhere('z.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('z.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ZoneRequirements
//    {
//        return $this->createQueryBuilder('z')
//            ->andWhere('z.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
