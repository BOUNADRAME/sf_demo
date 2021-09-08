<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Student;
use Faker\Provider\DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * encodeur password
     * 
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        // create admin user
        $userAdmin = new User();
        $userAdmin->setFirstName('ansd')
                ->setLastName('2021')
                ->setEmail('devops@ansd.sn')
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)))
                ->setPassword($this->encoder->encodePassword($userAdmin, 'password'))
                ->setPicture("https://randomuser.me/api/portraits/55.jpg")
                ->addUserRole($adminRole)
            ;
        $manager->persist($userAdmin);

        $users = [];
        $genres = ['male', 'female'];

        for ($i = 0; $i < 10; $i++){
            $user = new User();
            $genre = $faker->randomElement($genres);

            $picture = "https://randomuser.me/api/portraits/";
            $pictureId = $faker->numberBetween(1, 99).'jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)))
                 ->setPassword($hash)
                 ->setPicture($picture)
            ;
            $manager->persist($user);
            $users[] = $user;

        }
        
        for ($u = 0; $u < 15; $u++) {
            $student = new Student();

            $user = $users[mt_rand(0, count($users) - 1)];
            
            $student->setNom($faker->firstName())
                 ->setMatricule('MAT00'.$u)
                 ->setPrenom($faker->lastName())
                 ->setAge(mt_rand(14, 27))
                 ->setAuthor($user)
            ;

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