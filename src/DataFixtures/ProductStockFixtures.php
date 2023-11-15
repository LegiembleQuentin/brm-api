<?php

namespace App\DataFixtures;

use App\Entity\ProductStock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductStockFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $productStock = new ProductStock();
            $productStock->setStockQuantity($faker->randomFloat(1, 0.1, 50));
            $productStock->setUnit('Kilogramme');

            $stockReference = 'stock-' . rand(0, 9);
            $stock = $this->getReference($stockReference);
            $productStock->setStock($stock);

            $productReference = 'product-' . rand(0, 12);
            $product = $this->getReference($productReference);
            $productStock->setProduct($product);

            $manager->persist($productStock);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            StockFixtures::class
        ];
    }
}
