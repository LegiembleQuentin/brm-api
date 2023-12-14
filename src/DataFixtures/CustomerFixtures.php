<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CustomerFixtures extends Fixture
{
    public const CUSTOMER_REFERENCE = 'customer';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $customer = new Customer();
            $customer->setFirstname($faker->firstName);
            $customer->setLastname($faker->lastName);
            $customer->setEmail($faker->email);
            $customer->setAdress($faker->address);
            $customer->setCity($faker->city);
            $customer->setCountry($faker->countryCode);
            $customer->setCreatedAt(new \DateTimeImmutable());
            $customer->setLastCommand($faker->dateTimeBetween('-1 year', 'now'));
            $customer->setFidelityPoints($faker->numberBetween(1, 100));
            $customer->setPostalCode($faker->postcode);
            $postalCodeWithoutSpaces = str_replace(' ', '', $customer->getPostalCode());
            $customer->setPostalCode($postalCodeWithoutSpaces);

            $internationalPhone = $faker->phoneNumber;
            $numericPhone = preg_replace('/[^0-9]/', '', $internationalPhone);
            $formattedPhone = substr($numericPhone, 0, 10);
            $customer->setPhone($formattedPhone);

            $manager->persist($customer);

            $this->addReference('customer-' . $i, $customer);
        }

        $manager->flush();
    }
}
