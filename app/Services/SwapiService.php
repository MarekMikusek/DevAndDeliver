<?php

namespace App\Services;

use App\Helpers\MapResources;
use GuzzleHttp\Client;
use stdClass;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SwapiService
{
    protected $client;
    protected $header;
    protected $heroesList = [];
    protected $swapiUrl = 'http://swapi.dev/api';

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->heroesList = $this->getHeroesList();
        $this->header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];
    }

    public function resource(string $resource, int $id): object
    {
        $resourceName = MapResources::map($resource);
        $resourceUrl = "{$this->swapiUrl}/{$resourceName}/{$id}/";
        $userHero = (array)$this->heroesList[Auth::user()->hero];
        if (!$resourceName || !in_array($resourceUrl, $userHero['available_resources'])) {
            return new stdClass();
        }
        return $this->getApiResponse($resourceUrl);
    }

    public function getHeroResources(string $resource)
    {
        if ($resource === 'planets') {
            return $this->heroesList[Auth::user()->hero]['homeworld'];
        }
        $userHero = (array)$this->heroesList[Auth::user()->hero];
        return $userHero[$resource];
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
        $heroes = [];
        $pageNo = 1;
        $nextPage = 'http://swapi.dev/api/people/';
        do {
            $page = $this->getApiResponse($nextPage);
            foreach ($page->results as $hero) {
                $hero->available_resources = $this->getResourceList($hero);
                $heroes[] = $hero;
            }
            $pageNo++;
            $nextPage = $page->next;
        } while ($pageNo < 100 && $page && $nextPage);
        return $heroes;
    }

    protected function getApiResponse(string $endPoint)
    {
        if (Cache::has($endPoint)) {
            return Cache::get($endPoint);
        };
        try {
            $apiResponse = $this->client->request(
                "GET",
                $endPoint,
                ['headers' => $this->header]
            );
            Cache::put($endPoint, json_decode($apiResponse->getBody()), now()->addDay(1));
            return json_decode($apiResponse->getBody());
        } catch (ClientException $e) {
            return new stdClass();
        }
    }
}
