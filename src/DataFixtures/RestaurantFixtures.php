<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RestaurantFixtures extends Fixture
{
    public const RESTAURANT_REFERENCE = 'restaurant';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 15; $i++) {
            $restaurant = new Restaurant();
            $restaurant->setName($faker->city());
            $restaurant->setAdress($faker->streetAddress());
            $restaurant->setPostalCode($faker->postcode());
            $restaurant->setCity($faker->city());
            $restaurant->setCountry($faker->country());
            $restaurant->setEmail($faker->companyEmail());
            $restaurant->setPhone($faker->phoneNumber());
            $restaurant->setOperatingHours($faker->time($format = 'H:i') . '-' . $faker->time($format = 'H:i'));
            $restaurant->setRating($faker->randomFloat(2, 0, 5));
            $restaurant->setOpenDate($faker->dateTimeThisDecade());

            if ($faker->boolean(10)) {
                $restaurant->setCloseDate($faker->dateTimeBetween($restaurant->getOpenDate()));
            }

            $restaurant->setCreatedAt(new \DateTimeImmutable());
            $restaurant->setEnabled($faker->boolean(90));

            $manager->persist($restaurant);

            $this->addReference('restaurant-' . $i, $restaurant);
        }

        $manager->flush();
    }
}
