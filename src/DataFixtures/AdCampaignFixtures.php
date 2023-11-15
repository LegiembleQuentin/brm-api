<?php

namespace App\DataFixtures;

use App\Entity\AdCampaign;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AdCampaignFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $adCampaign = new AdCampaign();

            $adCampaign->setStartDate($faker->dateTimeThisDecade());
            $adCampaign->setCreatedAt(new \DateTimeImmutable());


            $adReference = 'ad-' . rand(0, 14);
            $ad = $this->getReference($adReference);
            $adCampaign->setAd($ad);

            $manager->persist($adCampaign);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AdFixtures::class,
        ];
    }
}
