<?php

namespace App\Http\Controllers;

use App\Client\ResourceClient;
use App\Services\PeopleService;

class TestController extends Controller
{

    public function __construct(private PeopleService $peopleService)
    {
        
    }

    public function getResourceTest() {
        dd(ResourceClient::getResource('starships'));
    }

    public function storePeople() {
        $this->peopleService->store(ResourceClient::getResource('people'));
    }
}
