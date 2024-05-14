<?php

namespace App\Repository;

use App\Entity\CofinancingScenario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CofinancingScenario>
 *
 * @method CofinancingScenario|null find($id, $lockMode = null, $lockVersion = null)
 * @method CofinancingScenario|null findOneBy(array $criteria, array $orderBy = null)
 * @method CofinancingScenario[]    findAll()
 * @method CofinancingScenario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CofinancingScenarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CofinancingScenario::class);
    }

    public function add(CofinancingScenario $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CofinancingScenario $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CofinancingScenario[] Returns an array of CofinancingScenario objects
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

//    public function findOneBySomeField($value): ?CofinancingScenario
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
