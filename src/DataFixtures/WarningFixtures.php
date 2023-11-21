<?php

namespace App\DataFixtures;

use App\Entity\Warning;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WarningFixtures extends Fixture implements DependentFixtureInterface
{
    private static $usedEmployeeReferences = [];
    private static $usedFeedbackReferences = [];
    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $warning = new Warning();

            $employeeReference = $this->generateUniqueEmployeeReference();
            $employee = $this->getReference($employeeReference);
            $warning->setEmployee($employee);

            $feedbackReference = $this->generateUniqueFeedbackReference();
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

    private function generateUniqueEmployeeReference()
    {
        do {
            $employeeReference = 'employee-' . rand(0, 99);
        } while (in_array($employeeReference, self::$usedEmployeeReferences));

        // Marquer la référence d'employé comme utilisée
        self::$usedEmployeeReferences[] = $employeeReference;

        return $employeeReference;
    }

    private function generateUniqueFeedbackReference()
    {
        do {
            $feedbackReference = 'feedback-' . rand(0, 64);
        } while (in_array($feedbackReference, self::$usedFeedbackReferences));

        // Marquer la référence de feedback comme utilisée
        self::$usedFeedbackReferences[] = $feedbackReference;

        return $feedbackReference;
    }
}
