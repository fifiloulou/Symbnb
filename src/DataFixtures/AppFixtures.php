<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Utilisation de faker pour remplir des fausses données
        $faker = Factory::create('fr_FR');
        
        // Je créé un nouveau role qui est le role admin
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole); 

        // Je créé un utilisateur qui à le role admin
        $adminUser = new User();
        $adminUser->setFirstName('Philippe')
                  ->setLastName('Favero')
                  ->setEmail('philippe.favero@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('https://lh3.googleusercontent.com/a-/AOh14Gjuap35tJTpOX00-dRPvxyQIbnVcc0zfkUK0ASE=s96-cc-rg')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);


        // Gestion des utilisateurs
        $users =[];
        $genres = ['male', 'female'];

        for($i = 1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            // $picture = $picture . ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // Gestions des annonces
        // Création d'une boucle qui va répéter 30 fois l'annonce
        for($i = 1; $i <= 30; $i++) {

            // Ici on créé une nouvelle annonce ad et on ajoute le use pour dire à php où elle se trouve
            $ad = new Ad();

            // On défini tous nos faker dans les variables
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user =$users[mt_rand(0, count($users) - 1)];


            // Création d'une annonce en utilisant les variables pour compléter l'annonce
            $ad->setTitle("$title")
                ->setCoverImage("$coverImage")
                ->setIntroduction("$introduction")
                ->setContent("$content")
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);
            
            // Permet d'enrichir les images
            for($j = 1; $j <= mt_rand(2,5); $j++) {
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                      ->setcaption($faker->sentence())
                      ->setAd($ad);
                    
                $manager->persist($image);
            }

            // Gestion des réservations
            for($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                // Gestion de la date fin
                $duration = mt_rand(3, 10);
                $endDate = (clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;

                $booker = $users[mt_rand(0, count($users) -1)];
                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($comment);

                $manager->persist($booking);
            }

            // On fait persister l'annonce dans la base de données
            $manager->persist($ad);
        }

        // Et on l'écrit en base de données (requête sql)
        $manager->flush();
    }
}
