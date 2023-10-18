<?php

namespace App\Repository;

use App\Entity\LossDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LossDetail>
 *
 * @method LossDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method LossDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method LossDetail[]    findAll()
 * @method LossDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LossDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LossDetail::class);
    }

//    /**
//     * @return LossDetail[] Returns an array of LossDetail objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LossDetail
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
