<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/wild/show/{slug}", requirements={"slug"="[a-z0-9-]+"},
     * defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"}, name="wild_show")
     */
    public function show($slug) :Response
    {
        return $this->render('wild/show.html.twig', [
            'slug' => $slug = ucwords(str_replace('-', ' ', $slug))
        ]);
    }

    /**
     * @Route("/blog/error/{slug}",
     * requirements={"slug"="[A-Z]+"},
     * defaults={"slug"="UPPER-CASE-NOT-ALLOWED"},
     * name="error")
     */
    public function error($slug)
    {
        // redirection vers la page erreur, correspondant à l'insertion de majuscule dans l'URL
        return $this->render('blog/error_404.html.twig', ['slug' => $slug]);
    }
}