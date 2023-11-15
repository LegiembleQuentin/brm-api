<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const CATEGORY_REFERENCE = 'category';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category->setName($faker->unique()->randomElement(['Boissons', 'Burgers', 'Frites']));
            $manager->persist($category);
            $this->addReference('category-' . $i, $category);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
        ];
    }
}