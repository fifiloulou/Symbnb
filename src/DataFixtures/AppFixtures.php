<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Création d'une boucle qui va répéter 30 fois l'annonce
        for($i = 1; $i <= 30; $i++) {
            // ici on créé une nouvelle annonce ad et on ajoute le use pour dire à php où elle se trouve
            $ad = new Ad();

            // Création d'une annonce
            $ad->setTitle("Titre de l'annonce n°$i")
            ->setSlug("titre-de-l-annoce-n-$i")
            ->setCoverImage("http://placehold.it/1000x300")
            ->setIntroduction("Bonjour à tous c'est une introduction")
            ->setContent("<p>Je suis un contenu riche !</p>")
            ->setPrice(mt_rand(40, 200))
            ->setRooms(mt_rand(1, 5));
            
            // On fait persister l'annonce dans la base de données
            $manager->persist($ad);
        }
        // Et on l'écrit en base de données (requête sql)
        $manager->flush();
    }
}
