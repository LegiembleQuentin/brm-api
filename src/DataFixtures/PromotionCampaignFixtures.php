<?php

namespace App\DataFixtures;

use App\Entity\PromotionCampaign;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PromotionCampaignFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROMOTIONCAMPAIGN_REFERENCE = 'promotioncampaign';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $promotionCampaign = new PromotionCampaign();
            $promotionCampaign->setDescription($faker->sentence(3));
            $promotionCampaign->setStartDate(new \DateTimeImmutable('-10 Years'));
            $promotionCampaign->setEndDate(new \DateTimeImmutable('+10 Years'));
            $promotionCampaign->setEnabled($faker->boolean());

            $promotionReference = 'promotion-' . rand(0, 14);
            $promotion = $this->getReference($promotionReference);
            $promotionCampaign->setPromotion($promotion);

            $productReference = 'product-' . rand(0, 12);
            $product = $this->getReference($productReference);
            $promotionCampaign->setProduct($product);

            $categoryReference = 'category-' . rand(0, 2);
            $category = $this->getReference($categoryReference);
            $promotionCampaign->setCategory($category);

            $manager->persist($promotionCampaign);


            $this->addReference('promotioncampaign-' . $i, $promotionCampaign);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            PromotionFixtures::class,
            ProductFixtures::class,
            CategoryFixtures::class
        ];
    }
}
