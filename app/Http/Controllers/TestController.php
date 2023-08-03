<?php

namespace App\Http\Controllers;

use App\Client\ResourceClient;

class TestController extends Controller
{
    public function getResourceTest() {
        dd(ResourceClient::getResource('starships'));
    }
}
