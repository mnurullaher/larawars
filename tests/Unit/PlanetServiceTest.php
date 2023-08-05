<?php

namespace Tests\Unit;

use App\Client\ResourceClient;
use App\Services\PlanetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanetServiceTest extends TestCase
{
    use RefreshDatabase;
    private PlanetService $planetService;

    public function __construct(string $name)
    {
        $this->planetService = new PlanetService();
        parent::__construct($name);
    }

    public function test_create_and_update_properly(): void
    {
        $planetArr = ResourceClient::getResource('planets');
        $this->planetService->store($planetArr);

        $this->assertDatabaseCount('planets', 60);
        $this->assertDatabaseHas('planets', [
            'name' => 'Tatooine',
            'rotation_period' => '23',
            'orbital_period' => '304',
            'diameter' => '10465',
            'climate' => 'arid',
            'gravity' => '1 standard',
            'terrain' => 'desert',
            'surface_water' => '1',
            'population' => '200000'
        ]);

        $planetArr[0]->rotation_period = 'updated';
        $this->planetService->store($planetArr);

        $this->assertDatabaseCount('planets', 60);
        $this->assertDatabaseHas('planets', [
            'name' => 'Tatooine',
            'rotation_period' => 'updated',
            'orbital_period' => '304',
            'diameter' => '10465',
            'climate' => 'arid',
            'gravity' => '1 standard',
            'terrain' => 'desert',
            'surface_water' => '1',
            'population' => '200000'
        ]);
    }
}
