<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use App\Entity\Student;
use Faker\Provider\DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($u = 0; $u < 7; $u++) {
            $student = new Student();
            
            $student->setNom($faker->firstName())
                 ->setPrenom($faker->lastName())
                //  ->setDateNaissance($faker->DateTime())
                 ->setAge(mt_rand(14, 27));

            for ($j = 0; $j < mt_rand(2, 3); $j++){
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                      ->setCaption($faker->sentence())
                      ->setStudent($student);
                $manager->persist($image);
            }

            $manager->persist($student);

        }

        $manager->flush();
    }
}