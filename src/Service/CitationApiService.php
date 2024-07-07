<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CitationApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getRandomCitation(): array
    {
        $response = $this->client->request(
            'GET',
            'https://kaamelott.chaudie.re/api/random'
        );

        return $response->toArray();
    }
}
