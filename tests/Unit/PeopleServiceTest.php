<?php

namespace Tests\Unit;

use App\Models\People;
use App\Services\PeopleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class PeopleServiceTest extends TestCase
{
    use RefreshDatabase;

    private PeopleService $peopleService;

    private array $peopleArr = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->peopleService = new PeopleService();
        $people = People::factory()->count(20)->make();
        $this->peopleArr = TestUtils::getResourceArray($people);
    }

    public function test_store_new_record_properly(): void
    {
        $this->peopleService->store($this->peopleArr);

        $this->assertDatabaseCount('people', 20);
        $this->assertDatabaseHas('people', $this->peopleArr[0]->toArray());
    }

    public function test_do_not_recreate_record_with_same_name(): void
    {
        $this->peopleService->store($this->peopleArr);
        $this->peopleArr[0]->hair_color = 'updated';
        $this->peopleService->store($this->peopleArr);

        $this->assertDatabaseCount('people', 20);
        $this->assertDatabaseHas('people', [
            'hair_color' => 'updated',
        ]);
    }
}
