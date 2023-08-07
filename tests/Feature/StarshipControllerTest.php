<?php

namespace Tests\Feature;

use App\Client\ResourceClient;
use App\Models\User;
use App\Services\StarshipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\TestUtils;

class StarshipControllerTest extends TestCase
{
    use RefreshDatabase;

    private StarshipService $starshipService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->starshipService = new StarshipService();
        $this->starshipService->store(ResourceClient::getResource('starships'));
        $this->user = TestUtils::createUser();
    }

    public function test_should_return_all_starships(): void
    {
        $response = $this->actingAs($this->user)->get('/api/starships/index');
        $data = $response->json();

        $response->assertStatus(200);
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(36, $data['total']);

        Artisan::call('migrate:refresh');
    }

    public function test_should_return_one_starship(): void
    {
        $response = $this->actingAs($this->user)->get('/api/starships/1');
        $data = $response->json();
        $notFoundResponse = $this->get('/api/starships/99');
        $notFoundData = $notFoundResponse->json();

        $response->assertStatus(200);
        $notFoundResponse->assertStatus(200);
        $this->assertEquals('CR90 corvette', $data['name']);
        $this->assertEquals('Not Found', $notFoundData['error']);

        Artisan::call('migrate:refresh');
    }
}
