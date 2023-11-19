<?php

namespace App\DataFixtures;

use App\Entity\TimeSlot;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TimeSlotsFixtures extends Fixture implements DependentFixtureInterface
{

    public const TIMESLOTS_REFERENCE = 'timeslots';

    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {

            $timeSlots = new TimeSlot();

            $timeSlots->setStartTime($faker->dateTimeThisDecade());
            $timeSlots->setEndTime($faker->dateTimeThisDecade());

            $employeeReference = 'employee-' . rand(0, 99);
            $employee = $this->getReference($employeeReference);
            $timeSlots->setEmployee($employee);

            $manager->persist($timeSlots);
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
