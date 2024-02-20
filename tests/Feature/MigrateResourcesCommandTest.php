<?php

namespace Tests\Feature;

use App\Services\PeopleService;
use App\Services\PlanetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MigrateResourcesCommandTest extends TestCase
{
    use RefreshDatabase;

    private PlanetService $planetService;

    private PeopleService $peopleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->planetService = new PlanetService();
        $this->peopleService = new PeopleService();
    }

    public function test_migrate_all_resources_data(): void
    {

        $resources = [
            'people' => 82,
            'planets' => 60,
            'vehicles' => 39,
            'starships' => 36,
        ];
        Artisan::call('resources:migrate');

        foreach ($resources as $resource => $count) {
            $this->assertDatabaseCount($resource, $count);
        }
        $this->assertEquals(true, $this->planetService->getByName('Tatooine')->has_force);
        $this->assertNotEmpty($this->peopleService->getByName('Han Solo')->starships);
        $this->assertNotEmpty($this->peopleService->getByName('Anakin Skywalker')->vehicles);
    }
}
