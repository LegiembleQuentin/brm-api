<?php

namespace App\Repository;

use App\Entity\UserRestaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserRestaurant>
 *
 * @method UserRestaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRestaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRestaurant[]    findAll()
 * @method UserRestaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRestaurant::class);
    }

//    /**
//     * @return UserRestaurant[] Returns an array of UserRestaurant objects
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

//    public function findOneBySomeField($value): ?UserRestaurant
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
