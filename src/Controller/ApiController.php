<?php

namespace App\Controller;

use App\Entity\Citation;
use App\Repository\CitationRepository;
use App\Service\CitationApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    private $citationApiService;
    private $entityManager;

    public function __construct(CitationApiService $citationApiService, EntityManagerInterface $entityManager)
    {
        $this->citationApiService = $citationApiService;
        $this->entityManager = $entityManager;
    }

    #[Route('/generate-citation', name: 'generate_citation', methods: ['POST'])]
    public function generateCitation(): JsonResponse
    {
        $citationData = $this->citationApiService->getRandomCitation();

        $citation = new Citation();
        $citation->setTexte($citationData['citation']['citation']);
        $citation->setDate(new \DateTime());

        $this->entityManager->persist($citation);
        $this->entityManager->flush();

        return new JsonResponse([
            'status'    => 'Citation generated!',
            'citation'  => [
                'id'    => $citation->getId(),
                'texte' => $citation->getTexte(),
                'date'  => $citation->getDate()->format('Y-m-d H:i:s')
            ]
        ], 200);
    }

    #[Route('/delete-citation', name: 'delete_citation', methods: ['DELETE'])]
    public function deleteCitation(Request $request, CitationRepository $citationRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'] ?? null;

        if ($id === null) {
            return new JsonResponse(['status' => 'ID is required'], 400);
        }

        $citation = $citationRepository->find($id);

        if (!$citation) {
            return new JsonResponse(['status' => 'Citation not found'], 404);
        }

        $this->entityManager->remove($citation);
        $this->entityManager->flush();

        return new JsonResponse([
            'status' => 'Citation deleted', 
            'id' => $id
        ], 200);
    }
}
