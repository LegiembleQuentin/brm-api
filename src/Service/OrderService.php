<?php
namespace App\Service;
use App\Entity\Order;
use App\Filter\OrderFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderService
{
    private $em;
    private $validator;
    private $restaurantService;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @return Order[]
     */
    public function getOrders() : array
    {
        $orderRepo = $this->em->getRepository(Order::class);
        return $orderRepo->findAll();
    }

    public function getOrderById(int $id): ?Order
    {
        $orderRepo = $this->em->getRepository(Order::class);
        return $orderRepo->find($id);
    }

    /**
     * @return Order[]
     */
    public function findByFilter(OrderFilter $orderFilter) : array
    {
        $orderRepo = $this->em->getRepository(Order::class);
        return $orderRepo->findOrdersByFilter($orderFilter);
    }
}