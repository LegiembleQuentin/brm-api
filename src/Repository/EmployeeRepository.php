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

    public function findByFilter(EmployeeFilter $filters) : array
    {
        // Créez un objet QueryBuilder
        $qb = $this->createQueryBuilder('e');

        // Appliquez les filtres en fonction des propriétés de l'objet $filters
        if ($filters->getSearch()) {
            $qb->andWhere('e.name LIKE :search OR e.email LIKE :search')
                ->setParameter('search', '%' . $filters->getSearch() . '%');
        }
        if ($filters->getContractType()) {
            $qb->andWhere('e.contract_type = :contractType')
                ->setParameter('contractType', $filters->getContractType());
        }
        if ($filters->getRestaurant()) {
            $qb->andWhere('e.restaurant = :restaurant')
                ->setParameter('restaurant', $filters->getRestaurant());
        }
        if ($filters->getRole()) {
            $qb->andWhere('e.role = :role')
                ->setParameter('role', $filters->getRole());
        }

        // Ajoutez des filtres supplémentaires si nécessaire

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
