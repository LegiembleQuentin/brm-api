<?php

namespace App\Repository;

use App\Entity\Absences;
use App\Filter\AbsenceFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Absences>
 *
 * @method Absences|null find($id, $lockMode = null, $lockVersion = null)
 * @method Absences|null findOneBy(array $criteria, array $orderBy = null)
 * @method Absences[]    findAll()
 * @method Absences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Absences::class);
    }

    /**
     * @return Absences[] Returns an array of Absence objects
     */
    public function findAbsencesByFilter(AbsenceFilter $filters) : array
    {
        $qb = $this->createQueryBuilder('a');

        // Join the employee table
        $qb->innerJoin('a.employee', 'employee')
            ->addSelect('employee');

        if ($filters->getRestaurant()) {
            $qb->innerJoin('employee.restaurant', 'restaurant')
                ->addSelect('restaurant')
                ->andWhere('restaurant = :restaurant')
                ->setParameter('restaurant', $filters->getRestaurant());
        }

        if ($filters->getEmployee()) {
            $qb->andWhere('employee = :employee')
                ->setParameter('employee', $filters->getEmployee());
        }

        if ($filters->getRestaurant() && $filters->getRestaurant() != 0) {
            $qb->andWhere('restaurant.id = :restaurant')
                ->setParameter('restaurant', $filters->getRestaurant());
        }

        if ($filters->getDate()) {
            $date = $filters->getDate();
            $date->setTime(0, 0);

            $qb->andWhere(':date BETWEEN a.start_date AND a.end_date')
                ->setParameter('date', $date);
        }

        return $qb->getQuery()->getArrayResult();
    }

//    /**
//     * @return Absences[] Returns an array of Absences objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Absences
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
