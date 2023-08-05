<?php

namespace Tests\Unit;

use App\Client\ResourceClient;
use App\Services\VehicleService;
use Tests\TestCase;

class VehicleServiceTest extends TestCase
{
    private VehicleService $vehicleService;

    public function __construct(string $name)
    {
        $this->vehicleService = new VehicleService();
        parent::__construct($name);
    }

    public function test_create_and_update_properly(): void
    {
        $vehiclesArr = ResourceClient::getResource('vehicles');
        $this->vehicleService->store($vehiclesArr);

        $this->assertDatabaseCount('vehicles', 39);
        $this->assertDatabaseHas('vehicles', [
            'name' => 'Sand Crawler',
            'model' => 'Digger Crawler',
            'manufacturer' => 'Corellia Mining Corporation',
            'cost_in_credits' => '150000',
            'length' => '36.8',
            'max_atmosphering_speed' => '30',
            'crew' => '46',
            'passengers' => '30',
            'consumables' => '2 months',
            'vehicle_class' => 'wheeled'
        ]);

        $vehiclesArr[0]->model = 'updated model';
        $this->vehicleService->store($vehiclesArr);

        $this->assertDatabaseCount('vehicles', 39);
        $this->assertDatabaseHas('vehicles', [
            'name' => 'Sand Crawler',
            'model' => 'updated model',
            'manufacturer' => 'Corellia Mining Corporation',
            'cost_in_credits' => '150000',
            'length' => '36.8',
            'max_atmosphering_speed' => '30',
            'crew' => '46',
            'passengers' => '30',
            'consumables' => '2 months',
            'vehicle_class' => 'wheeled'
        ]);
    }
}
