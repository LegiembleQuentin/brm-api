<?php

namespace App\DataFixtures;

use App\Entity\UserRestaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserRestaurantFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 14; $i++) {
            $userRestaurant = new UserRestaurant();

            $restaurantReference = 'restaurant-' . rand(0, 4);
            $restaurant = $this->getReference($restaurantReference);
            $userRestaurant->setRestaurant($restaurant);

            $userReference = 'user-' . rand(0, 99);
            $user = $this->getReference($userReference);
            $userRestaurant->setUser($user);

            $manager->persist($userRestaurant);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
            UserFixtures::class
        ];
    }
}
