<?php
namespace App\Service;
use App\Entity\Stock;
use App\Filter\StockFilter;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StockService
{
    private $em;
    private $validator;
    private $restaurantService;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, RestaurantService $restaurantService)
    {
        $this->em = $entityManager;
        $this->restaurantService = $restaurantService;
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

    public function getStockById(int $id): ?Stock
    {
        $stockRepo = $this->em->getRepository(Stock::class);
        return $stockRepo->find($id);
    }

    public function save(Stock $stock): Stock
    {
        $restaurant = $this->restaurantService->getRestaurantById($stock->getRestaurant()->getId());

        if (!$restaurant){
            throw new Exception('Restaurant not found.');
        }

        $errors = $this->validator->validate($stock);
        if (count($errors) > 0) {
            throw new Exception('Invalid stock');
        }

        $stock->setRestaurant($restaurant);
        $stock->setCreatedAt(new DateTimeImmutable('now'));

        $this->em->persist($stock);
        $this->em->flush();

        return $stock;
    }

    public function update(Stock $stock): Stock
    {
        $existingStock = $this->em->getRepository(Stock::class)->find($stock);

        if (!$existingStock) {
            throw new Exception('Stock not found');
        }

        $reflClass = new ReflectionClass($stock);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflClass->getProperties() as $property) {
            $name = $property->getName();
            if ($name !== 'id' && !$property->getValue($existingStock) instanceof Collection) {
                $value = $propertyAccessor->getValue($stock, $name);
                $propertyAccessor->setValue($existingStock, $name, $value);
            }
        }

        $restaurantId = $stock->getRestaurant()->getId();
        $restaurant = $this->restaurantService->getRestaurantById($restaurantId);

        if(!$restaurant){
            throw new Exception('Restaurant not found');
        }
        $existingStock->setRestaurant($restaurant);

        $errors = $this->validator->validate($existingStock);
        if (count($errors) > 0){
            throw new Exception('Invalid stock');
        }

        $this->em->flush();

        return $existingStock;
    }
}