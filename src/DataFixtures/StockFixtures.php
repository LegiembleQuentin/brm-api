<?php

namespace App\DataFixtures;

use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class StockFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $productName = $faker->unique()->randomElement(['Tomate', 'Oignon', 'Cheddar', 'Salade', 'Oignon frit', 'Steak', 'Raclette', 'Galette de Pomme de Terre', 'Tenders de Poulet', 'Chèvre']);

            $stock = new Stock();
            $stock->setName($productName);
            $stock->setUnit('Kilogramme');

            $stock->setQuantity($this->generateRealisticQuantity($productName, $faker));

            // $stock->setLastRestockDate($faker->dateTimeThisDecade());
            $startDate = $faker->dateTimeBetween('-1 week', 'now');

            $stock->setLastRestockDate($startDate);
            $stock->setCreatedAt(new \DateTimeImmutable());

            $restaurantReference = 'restaurant-' . rand(0, 14);
            $restaurant = $this->getReference($restaurantReference);
            $stock->setRestaurant($restaurant);

            $manager->persist($stock);

            $this->addReference('stock-' . $i, $stock);
        }

        $manager->flush();
    }

    private function generateRealisticQuantity(string $productName, $faker): float
    {
        switch ($productName) {
            case 'Tomate':
                return $faker->randomFloat(2, 1, 5);
            case 'Oignon':
                return $faker->randomFloat(2, 0.5, 3);
            case 'Cheddar':
                return $faker->randomFloat(2, 0.2, 2);

            default:
                return $faker->randomFloat(2, 0.1, 50);
        }
    }
}
