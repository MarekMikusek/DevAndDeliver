<?php

namespace App\Http\Controllers;

use App\Helpers\ReturnData;
use App\Services\SwapiService;

class ResourceController extends Controller
{
    protected $service;
    protected $resourcesList = ['vehicles', 'films', 'starships', 'species', 'planets'];

    public function __construct(SwapiService $service)
    {
        $this->service = $service;
    }
    public function show($resource)
    {
        if(!in_array($resource, $this->resourcesList)){
            return ReturnData::create(['message' => 'invalid resource name']);
        }
        return ReturnData::create(['data' => $this->service->getHeroResources($resource)]);
    }
}
