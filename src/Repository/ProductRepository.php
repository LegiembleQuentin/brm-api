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

//    /**
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
