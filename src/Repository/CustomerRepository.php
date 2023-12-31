<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Filter\CustomerFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findCustomersByFilter(CustomerFilter $filters): array
    {
        $qb = $this->createQueryBuilder('e');

        if ($filters->getSearch() && $filters->getSearch() !== 'undefined') {
            $searchTerm = '%' . str_replace(' ', '%', $filters->getSearch()) . '%';
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('e.name', ':search'),
                    $qb->expr()->like('e.firstname', ':search'),
                    $qb->expr()->like('e.email', ':search'),
                    $qb->expr()->like(
                        $qb->expr()->concat('e.firstname', $qb->expr()->concat($qb->expr()->literal(' '), 'e.lastname')),
                        ':search'
                    ),
                    $qb->expr()->like(
                        $qb->expr()->concat('e.name', $qb->expr()->concat($qb->expr()->literal(' '), 'e.firstname')),
                        ':search'
                    )
                )
            )->setParameter('search', $searchTerm);
        }


        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Customer[] Returns an array of Customer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Customer
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
