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

            $openingHour = $faker->numberBetween(9, 12);
            $closingHour = $faker->numberBetween(14, 22);

            $openingTime = sprintf('%02d:00', $openingHour);
            $closingTime = sprintf('%02d:00', $closingHour);

            $restaurant->setOperatingHours($openingTime . '-' . $closingTime);

            $restaurant->setRating($faker->randomFloat(2, 0, 5));
            $startDate = $faker->dateTimeBetween('-10 years', 'now');
            $restaurant->setOpenDate($startDate);
            if ($faker->boolean(10)) {
                $endDate = $faker->dateTimeBetween($startDate, 'now');
                $restaurant->setCloseDate($endDate);
            }

            $restaurant->setCreatedAt(new \DateTimeImmutable());
            $restaurant->setEnabled($faker->boolean(90));

            $manager->persist($restaurant);

            $this->addReference('restaurant-' . $i, $restaurant);
        }

        $manager->flush();
    }
}
