<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EMPLOYEE_REFERENCE = 'employee';

    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);


        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $employee = new Employee();
            $employee->setRole($faker->randomElement(['EMPLOYEE', 'MANAGER', 'DIRECTOR']));
            $employee->setEmail($faker->email());
            $employee->setSexe($faker->randomElement(['M', 'F']));
            $employee->setName($faker->lastName());
            $employee->setFirstname($faker->firstName());
            $employee->setBirthdate($faker->dateTimeThisCentury());
            $employee->setHireDate($faker->dateTimeThisDecade());
            $employee->setPhone($faker->phoneNumber());
            $employee->setAddress($faker->address());
            $employee->setPostalCode($faker->postcode());
            $employee->setSocialSecurityNumber($faker->numerify('###-##-####'));
            $employee->setContractType($faker->randomElement(['FULL_TIME', 'PART_TIME', 'TEMPORARY', 'PROBATION']));
            $employee->setContractEndDate($faker->optional()->dateTimeInInterval('+1 year', '+5 years'));
            $employee->setDisability($faker->boolean(10));
            if ($employee->isDisability()) {
                $employee->setDisabilityDesc($faker->sentence());
            }
            $employee->setEnabled($faker->boolean(80));
            $employee->setCreatedAt(new \DateTimeImmutable());

            $restaurantReference = 'restaurant-' . rand(0, 4);
            $restaurant = $this->getReference($restaurantReference);
            $employee->setRestaurant($restaurant);
            $manager->persist($employee);

            $this->addReference('employee-' . $i, $employee);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
        ];
    }
}
