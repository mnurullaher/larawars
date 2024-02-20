<?php

namespace Tests\Unit;

use App\Models\People;
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

    private PeopleService $peopleService;

    private array $vehicleArr = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->peopleService = new PeopleService();
        $this->vehicleService = new VehicleService($this->peopleService);
        $vehicles = Vehicle::factory()->count(20)->make();
        $this->vehicleArr = TestUtils::getResourceArray($vehicles);
    }

    public function test_store_new_record_properly(): void
    {
        $this->vehicleService->store($this->vehicleArr);

        $this->assertDatabaseCount('vehicles', 20);
        $this->assertDatabaseHas('vehicles', $this->vehicleArr[0]->toArray());
    }

    public function test_do_not_recreate_record_with_same_name(): void
    {
        $this->vehicleService->store($this->vehicleArr);
        $this->vehicleArr[0]->model = 'updated';
        $this->vehicleService->store($this->vehicleArr);

        $this->assertDatabaseCount('vehicles', 20);
        $this->assertDatabaseHas('vehicles', [
            'model' => 'updated',
        ]);
    }

    public function test_attach_vehicle_to_person(): void
    {
        $people = People::factory()->count(2)->make();
        $peopleArr = TestUtils::getResourceArray($people);
        $this->peopleService->store($peopleArr);
        $this->vehicleService->store($this->vehicleArr);
        $this->vehicleService->attachToPerson($peopleArr[0]->name, $this->vehicleArr[0]->name);
        $owner = $this->peopleService->getByName($peopleArr[0]->name);

        $this->assertNotEmpty($owner->vehicles);
    }
}
