<?php

namespace Tests\Feature;

use App\Client\ResourceClient;
use App\Models\User;
use App\Services\VehicleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\TestUtils;

class VehicleControllerTest extends TestCase
{
    use RefreshDatabase;

    private VehicleService $vehicleService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vehicleService = new VehicleService();
        $this->vehicleService->store(ResourceClient::getResource('vehicles'));
        $this->user = TestUtils::createUser();
    }

    public function test_should_return_all_vehicles(): void
    {
        $response = $this->actingAs($this->user)->get('/api/vehicles/index');
        $data = $response->json();

        $response->assertStatus(200);
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(39, $data['total']);

        Artisan::call('migrate:refresh');
    }

    public function test_should_return_one_vehicles(): void
    {
        $response = $this->actingAs($this->user)->get('/api/vehicles/1');
        $data = $response->json();
        $notFoundResponse = $this->get('/api/vehicles/99');
        $notFoundData = $notFoundResponse->json();

        $response->assertStatus(200);
        $notFoundResponse->assertStatus(200);
        $this->assertEquals('Sand Crawler', $data['name']);
        $this->assertEquals('Not Found', $notFoundData['error']);

        Artisan::call('migrate:refresh');
    }
}
