<?php

namespace App\DataFixtures;

use App\Entity\Warning;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WarningFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $warning = new Warning();

            $employeeReference = 'employee-' . rand(0, 99);
            $employee = $this->getReference($employeeReference);
            $warning->setEmployee($employee);

            $feedbackReference = 'feedback-' . rand(0, 64);
            $feedback = $this->getReference($feedbackReference);
            $warning->setFeedback($faker->randomElement([$feedback, null]));
            $warning->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($warning);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EmployeeFixtures::class,
            FeedbackFixtures::class
        ];
    }
}
