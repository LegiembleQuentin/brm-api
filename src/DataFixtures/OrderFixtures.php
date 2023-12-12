<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORDER_REFERENCE = "order";

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 700; $i++) {
            $order = new Order();
            $order->setDate($faker->dateTimeThisDecade());
            $order->setStatus($faker->randomElement(['Paid', 'Canceled']));

            $customerReference = 'customer-' . rand(0, 49);
            $customer = $this->getReference($customerReference);
            $order->setCustomer($customer);

            // Associez les produits à la commande
            $orderProducts = $this->generateOrderProducts($manager, $order);

            // Calculez le total en fonction des produits associés à la commande
            $totalPrice = $this->calculateTotalPrice($orderProducts);
            $order->setPrice($totalPrice);

            $manager->persist($order);

            $this->addReference('order-' . $i, $order);
        }

        $manager->flush();
    }

    private function generateOrderProducts(ObjectManager $manager, Order $order): array
    {
        $faker = Factory::create('fr_FR');
        $orderProducts = [];

        for ($i = 0; $i < 3; $i++) {
            $orderProduct = new OrderProduct();
            $orderProduct->setQuantity($faker->randomFloat(0, 0, 6));
            $orderProduct->setDate($faker->dateTimeThisDecade());

            $promotionCampaignReference = 'promotioncampaign-' . rand(0, 9);
            $promotionCampaign = $this->getReference($promotionCampaignReference);
            $orderProduct->setPromotionCampaign($promotionCampaign);

            $orderProduct->setAssociatedOrder($order);

            $productReference = 'product-' . rand(0, 12);
            $product = $this->getReference($productReference);
            $orderProduct->setProduct($product);

            $manager->persist($orderProduct);
            $orderProducts[] = $orderProduct;
        }

        return $orderProducts;
    }

    private function calculateTotalPrice(array $orderProducts): float
    {
        $totalPrice = 0;

        foreach ($orderProducts as $orderProduct) {
            $totalPrice += $orderProduct->getProduct()->getPrice() * $orderProduct->getQuantity();
        }

        return $totalPrice;
    }

    public function getDependencies(): array
    {
        return [
            CustomerFixtures::class,
            ProductFixtures::class,
            PromotionCampaignFixtures::class,
        ];
    }
}
