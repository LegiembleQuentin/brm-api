<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PromotionFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROMOTION_REFERENCE = 'promotion';
    private static $usedAdReferences = [];
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 15; $i++) {

            $promotion = new Promotion();

            $promotion->setTitle($faker->sentence());
            $promotion->setReduction($faker->randomFloat(2, 5, 50));
            $promotion->setCreatedAt(new \DateTimeImmutable());
            $promotion->setEnabled($faker->boolean());

            $adReference = $this->generateUniqueAdReference();
            $ad = $this->getReference($adReference);
            $promotion->setAd($ad);

            $manager->persist($promotion);

            $this->addReference('promotion-' . $i, $promotion);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AdFixtures::class,
        ];
    }

    private function generateUniqueAdReference()
    {
        do {
            $adReference = 'ad-' . rand(0, 14);
        } while (in_array($adReference, self::$usedAdReferences));

        // Marquer l'ad référence comme utilisée
        self::$usedAdReferences[] = $adReference;

        return $adReference;
    }
}
