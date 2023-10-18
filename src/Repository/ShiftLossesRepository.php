<?php

namespace App\Repository;

use App\Entity\ShiftLosses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShiftLosses>
 *
 * @method ShiftLosses|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiftLosses|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiftLosses[]    findAll()
 * @method ShiftLosses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiftLossesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShiftLosses::class);
    }

//    /**
//     * @return ShiftLosses[] Returns an array of ShiftLosses objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShiftLosses
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
