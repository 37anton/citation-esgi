<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Service\CitationApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/generate-citation', name: 'generate_citation', methods: ['POST'])]
    public function generateCitation(CitationApiService $citationApiService,
    EntityManagerInterface $em): JsonResponse
    {
        $citationData = $citationApiService->getRandomCitation();

        $citation = new Citation();
        $citation->setTexte($citationData['citation']);
        $citation->setDate(new \DateTime());

        $em->persist($citation);
        $em->flush();

        return new JsonResponse(['status' => 'Citation generated!'], 200);
    }
}
