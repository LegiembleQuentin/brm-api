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

    //chercher les moyennes des ventes sur une année
    public function getAverageSalesForYear()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        $monthsElapsed = $currentMonth;

        // Chiffre d'affaires total
        $qbTotalSales = $this->createQueryBuilder('o')
            ->select('SUM(o.price) as totalSales')
            ->where('SUBSTRING(o.date, 1, 4) = :currentYear')
            ->setParameter('currentYear', $currentYear);

        $totalSalesResult = $qbTotalSales->getQuery()->getSingleScalarResult();

        // Année bisextille?
        $daysInYear = (date('L') == 1) ? 366 : 365;

        // Moyenne des ventes par jour
        $averageSalesPerDay = $totalSalesResult / $daysInYear;

        // Ventes par mois
        $qbMonthly = $this->createQueryBuilder('o')
            ->select('SUBSTRING(o.date, 6, 2) as month', 'AVG(o.price) as averageSales')
            ->where('SUBSTRING(o.date, 1, 4) = :currentYear')
            ->setParameter('currentYear', $currentYear)
            ->groupBy('month');

        $monthly = $qbMonthly->getQuery()->getArrayResult();

        $averageSalesPerMonth = array_sum(array_column($monthly, 'averageSales')) / count($monthly);


        // Construire le tableau de données de vente
        $salesData = [
            'salesPerDay' => $averageSalesPerDay,
            'averageSalesPerMonth' => $averageSalesPerMonth,
            'salesPerMonth' => [],
            'totalSales' => $totalSalesResult
        ];

        foreach ($monthly as $monthData) {
            $salesData['salesPerMonth'][$monthData['month']] = $monthData['averageSales'];
        }

        return $salesData;
    }
}
