<?php

namespace App\Service;

use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;

class RestaurantService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Restaurant[]
     */
    public function getRestaurant() : array
    {
        $restaurantRepo = $this->entityManager->getRepository(Restaurant::class);
        return $restaurantRepo->findAll();
    }
}
