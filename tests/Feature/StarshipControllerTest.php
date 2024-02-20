<?php

namespace Tests\Feature;

use App\Models\Starship;
use App\Models\User;
use App\Services\PeopleService;
use App\Services\StarshipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\TestUtils;

class StarshipControllerTest extends TestCase
{
    use RefreshDatabase;

    private StarshipService $starshipService;

    private User $user;

    private array $starshipArr = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->starshipService = new StarshipService(new PeopleService());
        $starships = Starship::factory()->count(20)->make();
        $this->starshipArr = TestUtils::getResourceArray($starships);
        $this->user = TestUtils::createUser();
    }

    public function test_should_return_all_planets(): void
    {
        $this->starshipService->store($this->starshipArr);

        $response = $this->actingAs($this->user)->get('/api/starships');
        $data = $response->json()[0];

        $response->assertStatus(200);
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(20, $data['total']);
        Artisan::call('migrate:refresh');
    }

    public function test_should_return_one_planet(): void
    {
        $requestedId = count($this->starshipArr) - 1;
        $this->starshipService->store($this->starshipArr);

        $response = $this->actingAs($this->user)->get('/api/starships/'.$requestedId);
        $data = $response->json()[0];

        $response->assertStatus(200);
        $this->assertEquals($this->starshipArr[$requestedId - 1]->name, $data['name']);

        Artisan::call('migrate:refresh');
    }

    public function test_should_return_not_found_for_non_existed_planet(): void
    {
        $this->starshipService->store($this->starshipArr);

        $response = $this->actingAs($this->user)->get('/api/planets/'.count($this->starshipArr) + 1);
        $data = $response->json();

        $response->assertStatus(404);
        $this->assertEquals('Not Found', $data['error']);

        Artisan::call('migrate:refresh');
    }
}
