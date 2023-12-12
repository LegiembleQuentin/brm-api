<?php

namespace App\Repository;

use App\Entity\Order;
use App\Filter\OrderFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findOrdersByFilter(OrderFilter $filters) : array
    {
        $qb = $this->createQueryBuilder('o')
            ->leftJoin('o.orderProducts', 'op')
            ->leftJoin('op.product', 'p');

        if ($filters->getDate()) {
            $date = $filters->getDate();
            $startOfDay = $date->setTime(0, 0);
            $endOfDay = $date->setTime(23, 59, 59);

            $qb->andWhere('e.created_at BETWEEN :startOfDay AND :endOfDay')
                ->setParameter('startOfDay', $startOfDay)
                ->setParameter('endOfDay', $endOfDay);
        }

        if ($filters->getCustomer() && $filters->getCustomer() != 0) {
            $qb->andWhere('o.customer = :customer')
                ->setParameter('customer', $filters->getCustomer());
        }

        if ($filters->getStatus()) {
            $qb->andWhere('o.status = :status')
                ->setParameter('status', $filters->getStatus());
        }

        if ($filters->getProduct() && $filters->getProduct() != 0) {
            $qb->andWhere('p.id = :product')
                ->setParameter('product', $filters->getProduct());
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Order[] Returns an array of Order objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
