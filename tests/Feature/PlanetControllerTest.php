<?php

namespace Tests\Feature;

use App\Models\Planet;
use App\Models\User;
use App\Services\PlanetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\TestUtils;

class PlanetControllerTest extends TestCase
{
    use RefreshDatabase;
    private PlanetService $planetService;
    private User $user;
    private array $planetArr = array();

    protected function setUp(): void
    {
        parent::setUp();
        $this->planetService = new PlanetService();
        $planets = Planet::factory()->count(20)->make();
        $this->planetArr = TestUtils::getResourceArray($planets);
        $this->user = TestUtils::createUser();
    }

    public function test_should_return_all_planets(): void
    {
        $this->planetService->store($this->planetArr);

        $response = $this->actingAs($this->user)->get('/api/planets');
        $data = $response->json()[0];


        $response->assertStatus(200);
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(20, $data['total']);
        Artisan::call('migrate:refresh');
    }

    public function test_should_return_one_planet(): void
    {
        $requestedId =  count($this->planetArr) - 1;
        $this->planetService->store($this->planetArr);

        $response = $this->actingAs($this->user)->get('/api/planets/' . $requestedId);
        $data = $response->json()[0];

        $response->assertStatus(200);
        $this->assertEquals($this->planetArr[$requestedId-1]->name, $data['name']);

        Artisan::call('migrate:refresh');
    }

    public function test_should_return_not_found_for_non_existed_planet(): void
    {
        $this->planetService->store($this->planetArr);

        $response = $this->actingAs($this->user)->get('/api/planets/' . count($this->planetArr) + 1);
        $data = $response->json();

        $response->assertStatus(404);
        $this->assertEquals('Not Found', $data['error']);

        Artisan::call('migrate:refresh');
    }
}
