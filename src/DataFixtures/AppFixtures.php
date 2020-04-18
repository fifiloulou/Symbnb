<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Utilisation de faker pour remplir des fausses données
        $faker = Factory::create('fR-r');

        // Création d'une boucle qui va répéter 30 fois l'annonce
        for($i = 1; $i <= 30; $i++) {

            // Ici on créé une nouvelle annonce ad et on ajoute le use pour dire à php où elle se trouve
            $ad = new Ad();

            // On défini tous nos faker dans les variables
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';


            // Création d'une annonce en utilisant les variables pour compléter l'annonce
            $ad->setTitle("$title")
                ->setCoverImage("$coverImage")
                ->setIntroduction("$introduction")
                ->setContent("$content")
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5));
            
            // Permet d'enrichir les images
            for($j = 1; $j <= mt_rand(2,5); $j++) {
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                      ->setcaption($faker->sentence())
                      ->setAd($ad);
                    
                $manager->persist($image);
            }

            // On fait persister l'annonce dans la base de données
            $manager->persist($ad);
        }

        // Et on l'écrit en base de données (requête sql)
        $manager->flush();
    }
}
