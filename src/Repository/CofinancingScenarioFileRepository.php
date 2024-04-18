<?php

namespace App\Repository;

use App\Entity\CofinancingScenarioFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CofinancingScenarioFile>
 *
 * @method CofinancingScenarioFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method CofinancingScenarioFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method CofinancingScenarioFile[]    findAll()
 * @method CofinancingScenarioFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CofinancingScenarioFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CofinancingScenarioFile::class);
    }

    public function add(CofinancingScenarioFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CofinancingScenarioFile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return CofinancingScenarioFile[] Returns an array of CofinancingScenarioFile objects
     */
    public function findByStatusAndUser( $user, $status): array
    {
//        dd($user);
        return $this->createQueryBuilder('c')
            ->leftJoin('c.cofinancingScenario', 'cs')
            ->leftJoin('cs.user', 'u')
            ->andWhere('c.status = :status')
            ->andWhere('u.id = :id')
            ->setParameter('status', $status)
            ->setParameter('id', $user->getId())
            ->orderBy('c.id', 'ASC')
//            ->getFirstResult()
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?CofinancingScenarioFile
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
