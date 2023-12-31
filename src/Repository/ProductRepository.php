<?php

namespace App\Repository;

use App\Entity\Product;
use App\Filter\ProductFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findProductsByFilter(ProductFilter $filters) : array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.productStocks', 'ps')
            ->leftJoin('ps.stock', 's');


        $qb->addSelect('p', 'ps', 's');

        if ($filters->getSearch() && $filters->getSearch() !== 'undefined') {
            $searchTerm = '%' . str_replace(' ', '%', $filters->getSearch()) . '%';
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('p.name', ':search'),
                    $qb->expr()->like('p.description', ':search')
                )
            )->setParameter('search', $searchTerm);
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function getProductSales() : array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.name as productName')
            ->addSelect('SUM(op.quantity) as salesQuantity')
            ->addSelect('(SUM(op.quantity) * p.price) as price')
            ->leftJoin('p.orderProducts', 'op')
            ->groupBy('p.id')
            ->orderBy('salesQuantity', 'DESC');

        return $qb->getQuery()->getArrayResult();
    }
}
