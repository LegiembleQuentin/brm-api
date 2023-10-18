<?php

namespace App\Repository;

use App\Entity\StockOrderDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StockOrderDetail>
 *
 * @method StockOrderDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockOrderDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockOrderDetail[]    findAll()
 * @method StockOrderDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockOrderDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockOrderDetail::class);
    }

//    /**
//     * @return StockOrderDetail[] Returns an array of StockOrderDetail objects
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

//    public function findOneBySomeField($value): ?StockOrderDetail
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
