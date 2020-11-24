<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use stdClass;
use GuzzleHttp\Exception\ClientException;

class SwapiService
{
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
        $pageNo = 1;
        $nextPage = 'http://swapi.dev/api/people/';
        do {
            $page = $this->getApiResponse("{$nextPage}");
            foreach($page->results as $hero){
                $heroes[] = $hero;
            }
            $pageNo++;
            $nextPage = $page->next;
        } while ($pageNo < 100 && $page && $nextPage);
        return $heroes;
    }

    protected function getApiResponse(string $endPoint): object
    {
        try {
            $apiResponse = $this->client->request(
                "GET",
                $endPoint,
                ['headers' => $this->header]
            );
            dump(json_decode($apiResponse->getBody()));
            return json_decode($apiResponse->getBody());
        } catch(ClientException $e){
            return new stdClass();
        }
    }
}
