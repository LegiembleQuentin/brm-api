<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;

class RestaurantService
{
    private $entityManager;
    private $validator;
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @return Restaurant[]
     */
    public function getRestaurant(): array
    {
        $restaurantRepo = $this->entityManager->getRepository(Restaurant::class);
        return $restaurantRepo->findAll();
    }
    public function getRestaurantById(int $id): ?Restaurant
    {
        $restaurantRepo = $this->entityManager->getRepository(Restaurant::class);
        return $restaurantRepo->find($id);
    }

    public function save(Restaurant $restaurant): Restaurant
    {

        $errors = $this->validator->validate($restaurant);
        if (count($errors) > 0) {
            throw new Exception('Invalid employee');
        }

        $restaurant->setCreatedAt(new DateTimeImmutable('now'));

        $this->entityManager->persist($restaurant);
        $this->entityManager->flush();

        return $restaurant;
    }

    public function update(Restaurant $restaurant): Restaurant
    {
        $existingRestaurant = $this->entityManager->getRepository(Restaurant::class)->find($restaurant->getId());
        if (!$existingRestaurant) {
            throw new Exception('Restaurant not found.');
        }
        $reflClass = new ReflectionClass($restaurant);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($reflClass->getProperties() as $property) {
            $name = $property->getName();
            // Skip the ID property and collections
            if ($name !== 'id' && !$property->getValue($existingRestaurant) instanceof Collection) {
                $value = $propertyAccessor->getValue($restaurant, $name);
                $propertyAccessor->setValue($existingRestaurant, $name, $value);
            }
        }
        $errors = $this->validator->validate($restaurant);
        if (count($errors) > 0) {
            throw new Exception('Invalid restaurant');
        }
        $this->entityManager->flush();
        return $existingRestaurant;
    }
}
