<?php

namespace App\DataFixtures;

use App\Entity\ProductCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductCategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 15; $i++) {
            $productCategory = new ProductCategory();

            $productReference = 'product-' . rand(0, 12);
            $product = $this->getReference($productReference);
            $productCategory->setProduct($product);

            $categoryReference = 'category-' . rand(0, 2);
            $category = $this->getReference($categoryReference);
            $productCategory->setCategory($category);

            $manager->persist($productCategory);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            CategoryFixtures::class
        ];
    }
}
