<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Filter\EmployeeFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findEmployeesByFilter(EmployeeFilter $filters) : array
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
                        $qb->expr()->concat('e.firstname', $qb->expr()->concat($qb->expr()->literal(' '), 'e.name')),
                        ':search'
                    ),
                    $qb->expr()->like(
                        $qb->expr()->concat('e.name', $qb->expr()->concat($qb->expr()->literal(' '), 'e.firstname')),
                        ':search'
                    )
                )
            )->setParameter('search', $searchTerm);
        }

        if ($filters->getContractType() && $filters->getContractType() !== 'undefined') {
            $qb->andWhere('e.contract_type = :contractType')
                ->setParameter('contractType', $filters->getContractType());
        }
        if ($filters->getRestaurant() && $filters->getRestaurant() != 0) {
            $qb->andWhere('e.restaurant = :restaurant')
                ->setParameter('restaurant', $filters->getRestaurant());
        }
        if ($filters->getRole() && $filters->getRole() !== 'undefined') {
            $qb->andWhere('e.role = :role')
                ->setParameter('role', $filters->getRole());
        }
        if ($filters->isEnabled()){
            $qb->andWhere('e.enabled = :enabled')
                ->setParameter('enabled', 1);
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Employee[] Returns an array of Employee objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Employee
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
