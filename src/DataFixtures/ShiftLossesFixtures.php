<?php

namespace App\DataFixtures;

use App\Entity\ShiftLosses;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ShiftLossesFixtures extends Fixture
{
    public const SHIFTLOSSES_REFERENCE = 'shift_losses';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $shiftLosses = new ShiftLosses();
            $shiftLosses->setDate($faker->dateTimeThisDecade());
            $shiftLosses->setShift($faker->randomElement(['Après-Midi', 'Soir']));
            $shiftLosses->setCreatedAt(new \DateTimeImmutable());


            $manager->persist($shiftLosses);

            $this->addReference('shiftlosses-' . $i, $shiftLosses);
        }

        $manager->flush();
    }
}
