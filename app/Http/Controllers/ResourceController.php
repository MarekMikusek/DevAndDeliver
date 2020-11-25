<?php

namespace App\Http\Controllers;

use App\Helpers\ReturnData;
use App\Services\SwapiService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResourceController extends Controller
{
    protected $service;
    protected $resourcesList = ['vehicles', 'films', 'starships', 'species', 'planets'];

    public function __construct(SwapiService $service)
    {
        $this->service = $service;
    }

    public function heroResources(string $resource): JsonResponse
    {
        if (!in_array($resource, $this->resourcesList)) {
            return ReturnData::create(['message' => 'invalid resource name', 'code' => 400]);
        }
        return ReturnData::create(['data' => [$this->service->getHeroResources($resource)]]);
    }

    public function resources(string $resource, int $id)
    {
        $resource = $this->service->resource($resource, $id);
        if (empty((array)$resource)) {
            return ReturnData::create(['message' => 'bad request or unavailable resource', 'code' => 403]);
        }
        return ReturnData::create(['data' => $resource]);
    }
}
