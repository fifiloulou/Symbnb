<?php

namespace App\Controller;

use App\Entity\Ad;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index()
    {
        // Indique où récupèrer les données de la table ad
        $repo = $this->getDoctrine()->getRepository(Ad::class);

        // Précise de quelle manière récupérer les données
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }
}
