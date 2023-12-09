<?php
namespace App\Service;
use App\Entity\Stock;
use App\Filter\AbsenceFilter;
use App\Filter\StockFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StockService
{
    private $em;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
    }


    /**
     * @return Stock[]
     */
    public function getStocks() : array
    {
        $stockRepo = $this->em->getRepository(Stock::class);
        return $stockRepo->findAll();
    }

    /**
     * @return Stock[]
     */
    public function findByFilter(StockFilter $stockFilter) : array
    {
        $stockRepo = $this->em->getRepository(Stock::class);
        return $stockRepo->findStocksByFilter($stockFilter);
    }
}