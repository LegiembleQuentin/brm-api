<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $comment = new Comment();

            $comment->setDate($faker->dateTimeThisDecade());
            $comment->setContent($faker->text);
            $comment->setRating($faker->randomFloat(1, 0.1, 5));

            $customerReference = 'customer-' . rand(0, 49);
            $customer = $this->getReference($customerReference);
            $comment->setCustomer($customer);

            $manager->persist($comment);
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
