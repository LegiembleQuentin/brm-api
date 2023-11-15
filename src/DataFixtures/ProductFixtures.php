<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{

    public const PRODUCT_REFERENCE = 'product';

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 13; $i++) {
            $product = new Product();
            $category = new Category();

            $product->setName($faker->unique()->randomElement(['Americain', 'Epicurien', 'Montagnar', 'Berger', 'Costaud', 'Fétard', 'Frite', 'Frite Cheddar', 'Frite Cheddar Bacon', 'Coca Cola', 'Ice Tea', 'Fanta', 'Oasis']));
            $product->setDescription($faker->sentence());
            $product->setPrice($faker->randomFloat(2, 5, 15));
            $product->setImgUrl($faker->imageUrl());
            $product->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($product);

            $this->addReference('product-' . $i, $product);
        }

        $manager->flush();
    }
}
