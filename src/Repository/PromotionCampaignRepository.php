<?php

namespace App\Repository;

use App\Entity\PromotionCampaign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PromotionCampaign>
 *
 * @method PromotionCampaign|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionCampaign|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionCampaign[]    findAll()
 * @method PromotionCampaign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionCampaignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromotionCampaign::class);
    }

//    /**
//     * @return PromotionCampaign[] Returns an array of PromotionCampaign objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PromotionCampaign
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
