<?php

namespace Tests\Feature;

use App\Models\People;
use App\Models\Planet;
use App\Models\Starship;
use App\Models\User;
use App\Services\PeopleService;
use App\Services\PlanetService;
use App\Services\StarshipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class ImmigrationControllerTest extends TestCase
{
    use RefreshDatabase;

    private PeopleService $peopleService;

    private PlanetService $planetService;

    private StarshipService $starshipService;

    private array $peopleArr;

    private array $planetArr;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->peopleService = new PeopleService();
        $this->planetService = new PlanetService();
        $this->starshipService = new StarshipService($this->peopleService);
        $this->prepareDatabase();
        $this->user = TestUtils::createUser();
    }

    public function test_return_400_for_invalid_pilots(): void
    {
        $response = $this->actingAs($this->user)->post('/api/immigrate', [
            'pilot' => $this->peopleArr[1]->name,
            'immigrants' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name,
            ],
            'planet' => $this->planetArr[0]->name,
        ]);
        $data = $response->json();

        $response->assertStatus(400);
        $this->assertEquals('Pilots need to have starships!', $data['message']);
    }

    public function test_return_400_for_pilots_without_starships(): void
    {
        $response = $this->actingAs($this->user)->post('/api/immigrate', [
            'pilot' => 'Gandalf',
            'immigrants' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name,
            ],
            'planet' => $this->planetArr[0]->name,
        ]);
        $data = $response->json();

        $response->assertStatus(400);
        $this->assertEquals('You can not call a pilot from another universe!', $data['message']);
    }

    public function test_return_400_for_invalid_immigrants(): void
    {
        $response = $this->actingAs($this->user)->post('/api/immigrate', [
            'pilot' => $this->peopleArr[0]->name,
            'immigrants' => [
                $this->peopleArr[0]->name, 'Gandalf',
            ],
            'planet' => $this->planetArr[0]->name,
        ]);
        $data = $response->json();

        $response->assertStatus(400);
        $this->assertEquals(
            'Only people belongs to this universe can immigrate in this lands!',
            $data['message']
        );
    }

    public function test_return_400_for_immigrants_name_duplication(): void
    {
        $response = $this->actingAs($this->user)->post('/api/immigrate', [
            'pilot' => $this->peopleArr[0]->name,
            'immigrants' => [
                $this->peopleArr[0]->name, $this->peopleArr[0]->name,
            ],
            'planet' => $this->planetArr[0]->name,
        ]);
        $data = $response->json();

        $response->assertStatus(400);
        $this->assertEquals('Duplication in immigrant name', $data['message']);
    }

    public function test_return_400_for_already_immigrated_immigrants(): void
    {
        $response = $this->actingAs($this->user)->post('/api/immigrate', [
            'pilot' => $this->peopleArr[0]->name,
            'immigrants' => [
                $this->peopleArr[1]->name, $this->peopleArr[2]->name,
            ],
            'planet' => $this->planetArr[0]->name,
        ]);
        $data = $response->json();

        $response->assertStatus(400);
        $this->assertEquals(
            $this->peopleArr[2]->name.' has already immigrated', $data['message']
        );
    }

    public function test_return_400_for_non_existed_planets(): void
    {
        $response = $this->actingAs($this->user)->post('/api/immigrate', [
            'pilot' => $this->peopleArr[0]->name,
            'immigrants' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name,
            ],
            'planet' => 'Middle Earth',
        ]);
        $data = $response->json();

        $response->assertStatus(400);
        $this->assertEquals(
            'Immigrate to non-existed planets is impossible', $data['message']
        );
    }

    public function test_return_go_back_message_for_overpopulated_planets(): void
    {
        $response = $this->actingAs($this->user)->post('/api/immigrate', [
            'pilot' => $this->peopleArr[0]->name,
            'immigrants' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name,
            ],
            'planet' => $this->planetArr[1]->name,
        ]);
        $data = $response->json();

        $response->assertStatus(200);
        $this->assertEquals(
            'Go back to your homeland! '.$this->planetArr[1]->name.' can\'t accept more people!',
            $data['message']
        );
    }

    public function test_immigration_takes_place_with_valid_conditions(): void
    {
        $immigrantOneName = $this->peopleArr[0]->name;
        $immigrantTwoName = $this->peopleArr[1]->name;
        $planetName = $this->planetArr[0]->name;
        $population = $this->planetService->getByName($planetName)->population;
        $response = $this->actingAs($this->user)->post('/api/immigrate', [
            'pilot' => $this->peopleArr[0]->name,
            'immigrants' => [
                $immigrantOneName, $immigrantTwoName,
            ],
            'planet' => $planetName,
        ]);

        $data = $response->json();
        $immigrantOne = $this->peopleService->getByName($immigrantOneName);
        $immigrantTwo = $this->peopleService->getByName($immigrantTwoName);
        $planet = $this->planetService->getByName($planetName);

        $response->assertStatus(200);
        $this->assertEquals(
            'Welcome to '.$this->planetArr[0]->name.'\'s generous lands!',
            $data['message']
        );
        $this->assertEquals($planet->id, $immigrantOne->immigrated_planet_id);
        $this->assertEquals($planet->id, $immigrantTwo->immigrated_planet_id);
        $this->assertEquals($planet->population, intval($population) + 2);
    }

    private function prepareDatabase(): void
    {
        $planetArr = TestUtils::getResourceArray(Planet::factory()->count(2)->make());
        $this->planetArr = $planetArr;
        $this->planetService->store($planetArr);
        $overPopulate = $this->planetService->getByName($this->planetArr[1]->name);
        $overPopulate->population = '2200000';
        $overPopulate->update();

        $peopleArr = TestUtils::getResourceArray(People::factory()->count(3)->make());
        $this->peopleArr = $peopleArr;
        $this->peopleService->store($peopleArr);
        $immigrated = $this->peopleService->getByName($peopleArr[2]->name);
        $immigrated->immigrated_planet_id = Planet::where('name', $planetArr[0]->name)->first()->id;
        $immigrated->update();

        $starshipArr = TestUtils::getResourceArray(Starship::factory()->count(1)->make());
        $this->starshipService->store($starshipArr);
        $this->starshipService->attachToPerson($peopleArr[0]->name, $starshipArr[0]->name);
    }
}
