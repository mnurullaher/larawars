<?php

namespace Tests\Unit;

use App\Models\People;
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
    private PeopleService $peopleService;
    private array $starshipArr = array();

    protected function setUp(): void
    {
        parent::setUp();
        $this->peopleService = new PeopleService();
        $this->starshipService = new StarshipService($this->peopleService);
        $starships = Starship::factory()->count(20)->make();
        $this->starshipArr = TestUtils::getResourceArray($starships);
    }

    public function test_store_new_record_properly(): void
    {
        $this->starshipService->store($this->starshipArr);

        $this->assertDatabaseCount('starships', 20);
        $this->assertDatabaseHas('starships', $this->starshipArr[0]->toArray());
    }

    public function test_do_not_recreate_record_with_same_name(): void
    {
        $this->starshipService->store($this->starshipArr);
        $this->starshipArr[0]->model = 'updated';
        $this->starshipService->store($this->starshipArr);

        $this->assertDatabaseCount('starships', 20);
        $this->assertDatabaseHas('starships', [
            'model' => 'updated'
        ]);
    }

    public function test_attach_starship_to_person(): void
    {
        $people = People::factory()->count(1)->make();
        $peopleArr = TestUtils::getResourceArray($people);
        $this->peopleService->store($peopleArr);
        $this->starshipService->store($this->starshipArr);
        $this->starshipService->attachToPerson($peopleArr[0]->name, $this->starshipArr[0]->name);
        $owner = $this->peopleService->getByName($peopleArr[0]->name);

        $this->assertNotEmpty($owner->starships);
    }
}
