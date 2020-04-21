<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        // Indique où récupèrer les données de la table ad(les annonces)Mis en commentaire car remplacer par la ligne du dessus
        // $repo = $this->getDoctrine()->getRepository(Ad::class);

        // Précise de quelle manière récupérer les données
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Permet de créer une annonce
     * 
     * @Route("/ads/new", name="ads_create")
     *
     * @return Responce
     */
    public function create(Request $request, EntityManagerInterface $manager) {
        $ad = new Ad();

        // Ici j'appel le formulaire AdType.php dans Form
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            return$this->redirectToRoute('ads_show', [
                'slug' =>$ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     * 
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show(Ad $ad) {
        // Récupère l'annonce qui correspond au slug 
        //$ad = $repo->findOneBySlug($slug); cette ligne a été remplacé par ad $ad au dessus

        return $this->render('ad/show.html.twig', [
           'ad' => $ad 
        ]);
    }

}
