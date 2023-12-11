<?php

namespace App\DataFixtures;

use App\Entity\LossDetail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LossDetailFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $lossDetail = new LossDetail();
            $lossDetail->setQuantity($faker->randomFloat(2, 0.1, 10));
            $lossDetail->setUnit('Unitée');

            $shiftLossesReference = 'shiftlosses-' . rand(0, 19);
            $shiftLosses = $this->getReference($shiftLossesReference);
            $lossDetail->setShiftLosses($shiftLosses);

            $stockReference = 'stock-' . rand(0, 9);
            $stock = $this->getReference($stockReference);
            $lossDetail->setStock($stock);

            $productReference = 'product-' . rand(0, 12);
            $product = $this->getReference($productReference);
            $lossDetail->setProduct($product);

            $manager->persist($lossDetail);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ShiftLossesFixtures::class,
            ProductFixtures::class,
            StockFixtures::class,
        ];
    }
}
