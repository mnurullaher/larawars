<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Services\PeopleService;
use App\Services\VehicleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\TestUtils;

class VehicleControllerTest extends TestCase
{
    use RefreshDatabase;

    private VehicleService $vehicleService;
    private User $user;
    private array $vehicleArr = array();

    protected function setUp(): void
    {
        parent::setUp();
        $this->vehicleService = new VehicleService(new PeopleService());
        $vehicles = Vehicle::factory()->count(20)->make();
        $this->vehicleArr = TestUtils::getResourceArray($vehicles);
        $this->user = TestUtils::createUser();
    }

    public function test_should_return_all_planets(): void
    {
        $this->vehicleService->store($this->vehicleArr);

        $response = $this->actingAs($this->user)->get('/api/vehicles');
        $data = $response->json()[0];


        $response->assertStatus(200);
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(20, $data['total']);
        Artisan::call('migrate:refresh');
    }

    public function test_should_return_one_planet(): void
    {
        $requestedId =  count($this->vehicleArr) - 1;
        $this->vehicleService->store($this->vehicleArr);

        $response = $this->actingAs($this->user)->get('/api/vehicles/' . $requestedId);
        $data = $response->json()[0];

        $response->assertStatus(200);
        $this->assertEquals($this->vehicleArr[$requestedId-1]->name, $data['name']);

        Artisan::call('migrate:refresh');
    }

    public function test_should_return_not_found_for_non_existed_planet(): void
    {
        $this->vehicleService->store($this->vehicleArr);

        $response = $this->actingAs($this->user)->get('/api/planets/' . count($this->vehicleArr) + 1);
        $data = $response->json();

        $response->assertStatus(404);
        $this->assertEquals('Not Found', $data['error']);

        Artisan::call('migrate:refresh');
    }
}
