<?php

namespace App\DataFixtures;

use App\Entity\StockOrderDetail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class StockOrderDetailFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $stockOrderDetails = new StockOrderDetail();
            $stockOrderDetails->setQuantity($faker->randomFloat(2, 1, 50));
            $stockOrderDetails->setUnit('Kilogramme');
            $stockOrderDetails->setPrice($faker->randomFloat(2, 10, 100));

            $stockReference = 'stock-' . rand(0, 9);
            $stock = $this->getReference($stockReference);
            $stockOrderDetails->setStock($stock);

            $stockOrderReference = 'stockorder-' . rand(0, 4);
            $stockOrder = $this->getReference($stockOrderReference);
            $stockOrderDetails->setStockOrder($stockOrder);

            $manager->persist($stockOrderDetails);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            StockOrderFixtures::class,
            StockFixtures::class
        ];
    }
}
