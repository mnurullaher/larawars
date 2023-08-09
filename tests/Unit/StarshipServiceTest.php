<?php

namespace Tests\Unit;

use App\Models\Starship;
use App\Services\PeopleService;
use App\Services\StarshipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class StarshipServiceTest extends TestCase
{
    use RefreshDatabase;
    private StarshipService $starshipService;
    private array $starshipArr = array();

    protected function setUp(): void
    {
        parent::setUp();
        $this->starshipService = new StarshipService(new PeopleService());
        $starships = Starship::factory()->count(20)->make();
        $this->starshipArr = TestUtils::getResourceArray($starships);
    }

    public function test_store_new_record_properly(): void
    {
        $this->starshipService->store($this->starshipArr);

        $this->assertDatabaseCount('starships', 20);
        $this->assertDatabaseHas('starships', $this->starshipArr[0]->toArray());
    }

    public function test_do_not_recreate_record_with_same_name() {
        $this->starshipService->store($this->starshipArr);
        $this->starshipArr[0]->model = 'updated';
        $this->starshipService->store($this->starshipArr);

        $this->assertDatabaseCount('starships', 20);
        $this->assertDatabaseHas('starships', [
            'model' => 'updated'
        ]);
    }
}
