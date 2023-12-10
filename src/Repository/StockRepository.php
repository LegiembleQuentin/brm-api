<?php

namespace App\Repository;

use App\Entity\Stock;
use App\Filter\StockFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stock>
 *
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    public function findStocksByFilter(StockFilter $filters) : array
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.restaurant', 'r')
            ->addSelect('r');

        if ($filters->getSearch() && $filters->getSearch() !== 'undefined') {
            $searchTerm = '%' . str_replace(' ', '%', $filters->getSearch()) . '%';
            $qb->andWhere(
                $qb->expr()->like('s.name', ':search')
            )->setParameter('search', $searchTerm);
        }

        if ($filters->getRestaurant() && $filters->getRestaurant() != 0) {
            $qb->andWhere('r.id = :restaurant')
            ->setParameter('restaurant', $filters->getRestaurant());
        }

        if ($filters->isAlert()){
            $qb->andWhere('s.quantity <= s.stock_level_alert');
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Stock[] Returns an array of Stock objects
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

//    public function findOneBySomeField($value): ?Stock
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
