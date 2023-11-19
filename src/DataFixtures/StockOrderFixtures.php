<?php

namespace App\DataFixtures;

use App\Entity\StockOrder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class StockOrderFixtures extends Fixture
{
    public const STOCKORDER = 'stockorder';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $stockOrder = new StockOrder();
            $stockOrder->setOrderDate($faker->dateTimeThisDecade());
            $stockOrder->setStatus($faker->randomElement(['Livré', 'En Transit', 'Enregistré']));
            if ($stockOrder->getStatus() === "En Transit" || $stockOrder->getStatus() === 'Enregistré') {
                $stockOrder->setExpectedDeliveryDate($faker->dateTimeBetween('now', '2023 years'));
            } else if ($stockOrder->getStatus() === 'Livré') {
                $stockOrder->setDeliveryDate($faker->dateTimeThisDecade());
            }
            $stockOrder->setTotalCost($faker->randomFloat(2, 200, 600));
            $stockOrder->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($stockOrder);


            $this->addReference('stockorder-' . $i, $stockOrder);
        }

        $manager->flush();
    }
}
