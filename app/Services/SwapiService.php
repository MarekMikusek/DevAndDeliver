<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use stdClass;
use GuzzleHttp\Exception\ClientException;

class SwapiService
{
    protected $apiUrl = 'http://swapi.dev/api/';
    protected $client;
    protected $header;
    protected $heroesList =[];

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->heroesList = $this->getHeroesList();
        $this->header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    public function getRandomHeroId(): int
    {
        if(!count($this->heroesList)){
            return 0;
        }
        return rand(1, count($this->heroesList));
    }

    public function getHeroesList(): array
    {
        $heroes = [];
        $id = 1;
        do {
            $hero = $this->getApiResponse("people/{$id}/");
            $heroes[$id] = $hero;
            $id++;

        } while ($id < 1000 && $hero);
        return $heroes;
    }

    protected function getApiResponse(string $endPoint): object
    {
        try {
            $apiResponse = $this->client->request(
                "GET",
                $this->apiUrl . $endPoint,
                ['headers' => $this->header]
            );
            return json_decode($apiResponse->getBody());
        } catch(ClientException $e){
            return new stdClass();
        }
    }
}
