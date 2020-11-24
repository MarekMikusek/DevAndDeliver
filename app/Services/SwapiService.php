<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use stdClass;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;

class SwapiService
{
    protected $client;
    protected $header;
    protected $heroesList = [];

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
        if (!count($this->heroesList)) {
            return 0;
        }
        return rand(1, count($this->heroesList));
    }

    public function getResourceList(object $hero): array
    {
        $hero = (array)$hero;
        $ret = [$hero['homeworld']];
        foreach (['vehicles', 'films', 'starships', 'species'] as $resource) {
            $ret = array_merge($ret, $hero[$resource]);
        }
        return $ret;
    }

    public function getHeroesList(): array
    {
        if ($heroes = Cache::has('heroes_list')) {
            return json_decode(Cache::get('heroes_list'));
        };
        $heroes = [];
        $pageNo = 1;
        $nextPage = 'http://swapi.dev/api/people/';
        do {
            $page = $this->getApiResponse("{$nextPage}");
            foreach ($page->results as $hero) {
                $hero->available_resources = $this->getResourceList($hero);
                $heroes[] = $hero;
            }
            $pageNo++;
            $nextPage = $page->next;
        } while ($pageNo < 100 && $page && $nextPage);
        Cache::put('heroes_list', json_encode($heroes), 86400);
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
            return json_decode($apiResponse->getBody());
        } catch (ClientException $e) {
            return new stdClass();
        }
    }
}
