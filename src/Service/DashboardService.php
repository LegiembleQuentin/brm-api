<?php
namespace App\Service;

use App\Entity\Absences;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DashboardService
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
    }

    public function getProductSales()
    {
        $productRepo = $this->em->getRepository(Product::class);
        $productSales = $productRepo->getProductSales();
        return $productSales;
    }

    public function getSales()
    {
        $orderRepo = $this->em->getRepository(Order::class);
        $sales = $orderRepo->getAverageSalesForYear();
        return $sales;
    }

    public function getAbsences()
    {
        $absenceRepo = $this->em->getRepository(Absences::class);
        $absences = $absenceRepo->getAbsenceRatesByRestaurant();
        return $absences;
    }

}