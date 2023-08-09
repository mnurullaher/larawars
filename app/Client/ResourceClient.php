<?php

namespace App\Client;

use Illuminate\Support\Facades\Http;

class ResourceClient
{

    public static function getResource(string $resourceType): array
    {

        $page = 1;
        $results = array();

        do {
            $response =  Http::get('https://swapi.dev/api/' . $resourceType . '/?page=' . $page);
            $next = json_decode($response->body())->next;
            $currentResult = json_decode($response->body())->results;
            foreach ($currentResult as $result) {
                array_push($results, $result);
            }
            $page++;
        } while ($next != null);

        return $results;
    }
}
