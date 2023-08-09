<?php

namespace Tests\Unit;

use App\Models\Vehicle;
use App\Services\PeopleService;
use App\Services\VehicleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class VehicleServiceTest extends TestCase
{
    use RefreshDatabase;
    private VehicleService $vehicleService;
    private array $vehicleArr = array();

    protected function setUp(): void
    {
        parent::setUp();
        $this->vehicleService = new VehicleService(new PeopleService());
        $vehicles = Vehicle::factory()->count(20)->make();
        $this->vehicleArr = TestUtils::getResourceArray($vehicles);
    }

    public function test_store_new_record_properly(): void
    {
        $this->vehicleService->store($this->vehicleArr);

        $this->assertDatabaseCount('vehicles', 20);
        $this->assertDatabaseHas('vehicles', $this->vehicleArr[0]->toArray());
    }

    public function test_do_not_recreate_record_with_same_name() {
        $this->vehicleService->store($this->vehicleArr);
        $this->vehicleArr[0]->model = 'updated';
        $this->vehicleService->store($this->vehicleArr);

        $this->assertDatabaseCount('vehicles', 20);
        $this->assertDatabaseHas('vehicles', [
            'model' => 'updated'
        ]);
    }
}
