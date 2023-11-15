<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORDER_REFERENCE = "order";
    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 70; $i++) {
            $order = new Order();
            $order->setDate($faker->dateTimeThisDecade());
            $order->setPrice($faker->randomFloat(2, 0, 90));
            $order->setStatus($faker->randomElement(['Paid', 'Canceled']));

            $customerReference = 'customer-' . rand(0, 49);
            $customer = $this->getReference($customerReference);
            $order->setCustomer($customer);

            $manager->persist($order);

            $this->addReference('order-' . $i, $order);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CustomerFixtures::class,
        ];
    }
}
