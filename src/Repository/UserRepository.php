<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findAllROIV(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :val')
            ->setParameter('val', "%ROLE_ROIV%")
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findAllBAS(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :val')
            ->setParameter('val', "%ROLE_BAS%")
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllClusterByRegion($region): array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->andWhere('u.roles LIKE :role1 or u.roles LIKE :role2')
            ->andWhere('uf.rf_subject = :region')
            ->andWhere('uf.year > :year')
            ->setParameter('role1', "%ROLE_SMALL_CLUSTERS%")
            ->setParameter('role2', "%ROLE_REGION%")
            ->setParameter('region', $region)
            ->setParameter('year', 2022)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAllCluster(): array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->andWhere('u.roles LIKE :role1 or u.roles LIKE :role2 or u.roles LIKE :role3')

            ->andWhere('uf.year > :year')
            ->setParameter('role1', "%ROLE_SMALL_CLUSTERS%")
            ->setParameter('role2', "%ROLE_REGION%")
            ->setParameter('role3', "%ROLE_BAS%")

            ->setParameter('year', 2022)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function getUserByUserInfo($userInfo): ?User
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->andWhere('uf.id = :id')
            ->setParameter('id', $userInfo->getId())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByApiToken($token): ?User
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.apiTokens', 'api')
            ->andWhere('api.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function findByYearAndRole($year, $role)
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->andWhere('u.roles LIKE :role ')
            ->andWhere('uf.year = :year')
            ->setParameter('role', "%$role%")
            ->setParameter('year', $year)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function getUsersByYearRoleTags($year, $role, $tags = null){



        $query = $this->createQueryBuilder('u')
            ->leftJoin('u.user_info', 'uf')
            ->andWhere('u.roles LIKE :role ')
            ->andWhere('uf.year = :year')
            ->setParameter('role', "%$role%")
            ->setParameter('year', $year)
            ->leftJoin('uf.rf_subject', 'rf')
        ;

        if($tags)
        {
            $query
                ->leftJoin('u.userTags', 't')
                ->andWhere('t.id = :tags')
                ->setParameter('tags', $tags->getId());
        }
        return $query
            ->orderBy('rf.name', 'ASC')
            ->getQuery()
            ->getResult();

    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
