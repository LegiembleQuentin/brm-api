<?php

namespace App\DataFixtures;

use App\Entity\Absences;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AbsencesFixtures extends Fixture implements DependentFixtureInterface
{

    public const ABSENCES_REFERENCE = 'absences';

    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 800; $i++) {

            $absences = new Absences();

            $startOfYear = new DateTime(date('Y-01-01'));
            $startDate = $faker->dateTimeBetween($startOfYear);
            $endDate = $faker->dateTimeInInterval($startDate, '+7 days');

            $absences->setStartDate($startDate);
            $absences->setEndDate($endDate);
            $absences->setReason($faker->randomElement([
                $faker->text(20), null
            ]));
            $absences->setApproved($faker->randomElement([
                true, false
            ]));
            $absences->setType($faker->text(10));
            $absences->setCreatedAt(new \DateTimeImmutable());

            $employeeReference = 'employee-' . rand(0, 99);
            $employee = $this->getReference($employeeReference);
            $absences->setEmployee($employee);

            $manager->persist($absences);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EmployeeFixtures::class,
        ];
    }
}
