<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(ProgramRepository $programRepository) :Response
    {
        $programs = $programRepository->findBy(
            [],
            ['id' => 'DESC'],
            3
        );
        return $this->render('home.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
        ]);
    }
}
