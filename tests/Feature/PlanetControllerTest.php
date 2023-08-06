<?php

namespace Tests\Feature;

use App\Client\ResourceClient;
use App\Services\PlanetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PlanetControllerTest extends TestCase
{
    use RefreshDatabase;
    private PlanetService $planetService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->planetService = new PlanetService();
        $this->planetService->store(ResourceClient::getResource('planets'));
    }

    public function test_should_return_all_planets(): void
    {
        $response = $this->get('/api/planets/index');
        $data = $response->json();

        $response->assertStatus(200);
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(60, $data['total']);

        Artisan::call('migrate:refresh');
    }

    public function test_should_return_one_planet(): void
    {
        $response = $this->get('/api/planets/1');
        $data = $response->json();
        $notFoundResponse = $this->get('/api/planets/99');
        $notFoundData = $notFoundResponse->json();

        $response->assertStatus(200);
        $notFoundResponse->assertStatus(200);
        $this->assertEquals('Tatooine', $data['name']);
        $this->assertEquals('Not Found', $notFoundData['error']);

        Artisan::call('migrate:refresh');
    }
}
