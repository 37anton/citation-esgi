<?php

namespace App\Controller;

use App\Repository\CitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CitationRepository $citationRepository): Response
    {
        $citations = $citationRepository->findAll();

        return $this->render('home/index.html.twig', [
            'citations' => $citations
        ]);
    }
}
