<?php

namespace Tests\Unit;

use App\Client\ResourceClient;
use App\Services\PeopleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeopleServiceTest extends TestCase
{
    use RefreshDatabase;
    private PeopleService $peopleService;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->peopleService = new PeopleService();
    }

    public function test_create_and_update_properly(): void
    {
        $peopleArr = ResourceClient::getResource('people');
        $this->peopleService->store($peopleArr);

        $this->assertDatabaseCount('people', 82);
        $this->assertDatabaseHas('people', [
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'blond',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male'
        ]);

        $peopleArr[0]->hair_color = 'updated hair color';
        $this->peopleService->store($peopleArr);

        $this->assertDatabaseCount('people', 82);
        $this->assertDatabaseHas('people', [
            'name' => 'Luke Skywalker',
            'height' => '172',
            'mass' => '77',
            'hair_color' => 'updated hair color',
            'skin_color' => 'fair',
            'eye_color' => 'blue',
            'birth_year' => '19BBY',
            'gender' => 'male'
        ]);
    }
}
