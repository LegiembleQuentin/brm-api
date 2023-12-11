<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AdFixtures extends Fixture
{
    public const AD_REFERENCE = 'ad';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 15; $i++) {

            $ad = new Ad();

            $ad->setName($faker->sentence(1));
            $ad->setBudget($faker->randomFloat(0, 350, 1000));
            $ad->setTargetAudience($faker->randomElement(['Enfant', 'Etudiant', 'Adulte', 'Famille', 'Couple']));
            $ad->setDescription($faker->sentence(3));
            $ad->setImgUrl($faker->imageUrl());
            $ad->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($ad);

            $this->addReference('ad-' . $i, $ad);
        }

        $manager->flush();
    }
}
