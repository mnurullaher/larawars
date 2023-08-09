<?php

namespace Tests\Unit;

use App\Models\Planet;
use App\Services\PlanetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class PlanetServiceTest extends TestCase
{
    use RefreshDatabase;
    private PlanetService $planetService;
    private array $planetArr = array();

    protected function setUp(): void
    {
        parent::setUp();
        $this->planetService = new PlanetService();
        $planets = Planet::factory()->count(20)->make();
        $this->planetArr = TestUtils::getResourceArray($planets);
    }

    public function test_store_new_record_properly(): void
    {
        $this->planetService->store($this->planetArr);

        $this->assertDatabaseCount('planets', 20);
        $this->assertDatabaseHas('planets', $this->planetArr[0]->toArray());
    }

    public function test_do_not_recreate_record_with_same_name() {
        $this->planetService->store($this->planetArr);
        $this->planetArr[0]->diameter = 'updated';
        $this->planetService->store($this->planetArr);

        $this->assertDatabaseCount('planets', 20);
        $this->assertDatabaseHas('planets', [
            'diameter' => 'updated'
        ]);
    }
}
