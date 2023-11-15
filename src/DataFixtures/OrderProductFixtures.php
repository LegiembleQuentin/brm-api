<?php

namespace App\DataFixtures;

use App\Entity\OrderProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 1; $i++) {
            $orderProduct = new OrderProduct();
            $orderProduct->setQuantity($faker->randomFloat(0, 0, 6));
            $orderProduct->setDate($faker->dateTimeThisDecade());

            $promotionCampaignReference = 'promotioncampaign-' . rand(0, 9);
            $promotionCampaign = $this->getReference($promotionCampaignReference);
            $orderProduct->setPromotionCampaign($promotionCampaign);

            $orderReference = 'order-' . rand(0, 69);
            $order = $this->getReference($orderReference);
            $orderProduct->setAssociatedOrder($order);

            $productReference = 'product-' . rand(0, 12);
            $product = $this->getReference($productReference);
            $orderProduct->setProduct($product);

            $manager->persist($orderProduct);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OrderFixtures::class,
            ProductFixtures::class,
            PromotionCampaignFixtures::class,
        ];
    }
}
