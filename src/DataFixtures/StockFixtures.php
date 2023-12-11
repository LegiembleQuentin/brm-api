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
            $stock = new Stock();
            $stock->setName($faker->unique()->randomElement(['Tomate', 'Oignon', 'Cheddar', 'Salade', 'Oignon frit', 'Steak', 'Raclette', 'Galette de Pomme de Terre', 'Tenders de Poulet', 'Chèvre']));
            $stock->setQuantity($faker->randomFloat(2, 0.1, 50));
            $stock->setUnit('Kilogramme');
            $stock->setLastRestockDate($faker->dateTimeThisDecade());
            $stock->setCreatedAt(new \DateTimeImmutable());

            $restaurantReference = 'restaurant-' . rand(0, 14);
            $restaurant = $this->getReference($restaurantReference);
            $stock->setRestaurant($restaurant);

            $manager->persist($stock);

            $this->addReference('stock-' . $i, $stock);
        }

        $manager->flush();
    }
}
