<?php

namespace App\DataFixtures;

use App\Entity\EmailHistory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EmailHistoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $emailHistory = new EmailHistory();

            $emailHistory->setRecipientEmail($faker->email);
            $emailHistory->setSubject($faker->sentence(1));
            $emailHistory->setContent($faker->sentence(5));
            $emailHistory->setSentDate($faker->dateTimeThisDecade());

            $adReference = 'ad-' . rand(0, 14);
            $ad = $this->getReference($adReference);
            $emailHistory->setAd($ad);

            $manager->persist($emailHistory);
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
