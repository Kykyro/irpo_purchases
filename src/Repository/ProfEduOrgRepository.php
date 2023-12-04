<?php

namespace App\Repository;

use App\Entity\ProfEduOrg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfEduOrg>
 *
 * @method ProfEduOrg|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfEduOrg|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfEduOrg[]    findAll()
 * @method ProfEduOrg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfEduOrgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfEduOrg::class);
    }

    public function add(ProfEduOrg $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProfEduOrg $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ProfEduOrg[] Returns an array of ProfEduOrg objects
     */
    public function findAllByRegion($regionId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.region = :id')
            ->setParameter('id', $regionId)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByRegionAndYear($regionId, $year): array
    {
        return $this->createQueryBuilder('p')

            ->andWhere('p.region = :id')
            ->andWhere('p.year = :year')
            ->setParameter('id', $regionId)
            ->setParameter('year', $year)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findAllByYear($year): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.region', 'region')
            ->andWhere('p.year = :year')
            ->setParameter('year', $year)
            ->orderBy('region.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?ProfEduOrg
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
