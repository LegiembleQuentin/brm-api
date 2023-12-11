<?php

namespace App\DataFixtures;

use App\Entity\Feedback;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FeedbackFixtures extends Fixture implements DependentFixtureInterface
{

    public const FEEDBACK_REFERENCE = 'feedback';

    public function load(ObjectManager $manager): void
    {
        //        $connection = $manager->getConnection();
        //        $connection->setNestTransactionsWithSavepoints(true);

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 65; $i++) {

            $feedback = new Feedback();

            $employeeReference = 'employee-' . rand(0, 99);
            $employee = $this->getReference($employeeReference);


            $feedback->setContent($faker->text(200));
            $feedback->setWarning($faker->randomElement([true, false]));
            $randomDate = $faker->dateTimeBetween('-1 year', 'now');
            $feedback->setCreatedAt(\DateTimeImmutable::createFromMutable($randomDate));

            $feedback->setEmployee($faker->randomElement([
                $employee, null
            ]));

            $authorReference = 'employee-' . rand(0, 99);
            $author = $this->getReference($authorReference);
            $feedback->setAuthor($author);

            $manager->persist($feedback);

            $this->addReference('feedback-' . $i, $feedback);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EmployeeFixtures::class,
        ];
    }
}
