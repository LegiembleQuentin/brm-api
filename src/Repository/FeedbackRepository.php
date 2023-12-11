<?php

namespace App\Repository;

use App\Entity\Feedback;
use App\Filter\FeedbackFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Feedback>
 *
 * @method Feedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedback[]    findAll()
 * @method Feedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    /**
     * @return Feedback[] Returns an array of Feedback objects
     */
    public function findFeedbacksByFilter(FeedbackFilter $filters) : array
    {
        $qb = $this->createQueryBuilder('e');

        $qb->leftJoin('e.employee', 'employee')
            ->addSelect('employee')
            ->leftJoin('e.author', 'author')
            ->addSelect('author');

        if ($filters->isWarning()){
            $qb->andWhere('e.warning = :warning')
                ->setParameter('warning', 1);
        }

        if ($filters->getEmployee() && $filters->getEmployee() != 0) {
            $qb->andWhere('e.employee = :employee')
                ->setParameter('employee', $filters->getEmployee());
        }

        if ($filters->getAuthor() && $filters->getAuthor() != 0) {
            $qb->andWhere('e.author = :author')
                ->setParameter('author', $filters->getAuthor());
        }

        if ($filters->getDate()) {
            $date = $filters->getDate();
            $startOfDay = $date->setTime(0, 0);
            $endOfDay = $date->setTime(23, 59, 59);

            $qb->andWhere('e.created_at BETWEEN :startOfDay AND :endOfDay')
                ->setParameter('startOfDay', $startOfDay)
                ->setParameter('endOfDay', $endOfDay);
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Feedback[] Returns an array of Feedback objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Feedback
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
