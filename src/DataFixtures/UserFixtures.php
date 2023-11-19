<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setEmail($faker->email);
            $user->setPassword($faker->md5());
            $user->setRoles((['Manager', 'Responsable Marketing', 'Directeur', 'Administrateur']));
            $user->setEnabled($faker->randomElement([true, false]));
            $user->setInvitationToken($faker->md5());
            $user->setInvitationTokenExpiry($faker->dateTimeThisDecade());
            $user->setCreatedAt(new \DateTimeImmutable());

            $this->addReference('user-' . $i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
