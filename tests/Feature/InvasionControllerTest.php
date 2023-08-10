<?php

namespace Tests\Feature;

use App\Models\Invasion;
use App\Models\People;
use App\Models\Planet;
use App\Models\Starship;
use App\Models\Vehicle;
use App\Services\PeopleService;
use App\Services\PlanetService;
use App\Services\StarshipService;
use App\Services\VehicleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestUtils;

class InvasionControllerTest extends TestCase
{
    use RefreshDatabase;
    private PeopleService $peopleService;
    private PlanetService $planetService;
    private StarshipService $starshipService;
    private VehicleService $vehicleService;
    private array $peopleArr;
    private array $planetArr;
    private array $starshipArr;
    private array $vehicleArr;

    protected function setUp(): void
    {
        parent::setUp();
        $this->peopleService = new PeopleService();
        $this->planetService = new PlanetService();
        $this->starshipService = new StarshipService($this->peopleService);
        $this->vehicleService = new VehicleService($this->peopleService);
        $this->prepareDatabase();

        Invasion::create([
            'title' => 'Test Invasion',
            'planet_id' => Planet::where('name', $this->planetArr[0]->name)->first()->id
        ]);
    }

    public function test_return_404_for_invalid_invaders(): void
    {
//        dd($this->planetArr[0]->id);
        $response = $this->post('/api/invade', [
            'title' => 'Test Invasion',
                'invaders' => [
                    $this->peopleArr[0]->name, $this->peopleArr[1]->name, 'gandalf'
                ],
                'planet' => $this->planetArr[0]
        ]);
        $data = $response->json();
        $this->assertEquals('Not this time. You may have gandalf in another universe!', $data['message']);
        $response->assertStatus(400);
    }

    public function test_return_404_for_few_invaders() {
        $response = $this->post('/api/invade', [
            'title' => 'Test Invasion',
            'invaders' => [
                $this->peopleArr[0]->name
            ],
            'planet' => $this->planetArr[0]
        ]);
        $data = $response->json();
        $this->assertEquals('You can not invade a planet with a handful of people!', $data['message']);
        $response->assertStatus(400);
    }

    public function test_return_404_for_invader_duplication() {
        $response = $this->post('/api/invade', [
            'title' => 'Test Invasion',
            'invaders' => [
                $this->peopleArr[0]->name, $this->peopleArr[0]->name
            ],
            'planet' => $this->planetArr[0]
        ]);
        $data = $response->json();
        $this->assertEquals('You can not call a person twice for an invasion!', $data['message']);
        $response->assertStatus(400);
    }

    public function test_return_404_for_lack_of_starships() {
        $response = $this->post('/api/invade', [
            'title' => 'Test Invasion',
            'invaders' => [
                $this->peopleArr[1]->name, $this->peopleArr[2]->name
            ],
            'planet' => $this->planetArr[0]
        ]);
        $data = $response->json();
        $this->assertEquals(
            'This expedition is not possible!. Those invaders don\'t have necessary equipments.',
            $data['message']);
        $response->assertStatus(400);
    }

    public function test_return_404_for_lack_of_vehicles() {
        $response = $this->post('/api/invade', [
            'title' => 'Test Invasion',
            'invaders' => [
                $this->peopleArr[0]->name, $this->peopleArr[2]->name
            ],
            'planet' => $this->planetArr[0]
        ]);
        $data = $response->json();
        $this->assertEquals(
            'This expedition is not possible!. Those invaders don\'t have necessary equipments.',
            $data['message']);
        $response->assertStatus(400);
    }

    public function test_return_404_for_already_invaded_planets() {
        $response = $this->post('/api/invade', [
            'title' => 'Test Invasion',
            'invaders' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name
            ],
            'planet' => $this->planetArr[0]->name
        ]);
        $data = $response->json();
        $this->assertEquals(
            'Planet has already invaded',
            $data['message']);
        $response->assertStatus(400);
    }

    public function test_return_404_for_non_existed_planets() {
        $response = $this->post('/api/invade', [
            'title' => 'Test Invasion',
            'invaders' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name
            ],
            'planet' => 'Middle Earth'
        ]);
        $data = $response->json();
        $this->assertEquals(
            'Non-existed planets cannot be invaded',
            $data['message']);
        $response->assertStatus(400);
    }

    public function test_create_invasion_for_valid_requests() {
        $response = $this->post('/api/invade', [
            'title' => 'Test Invasion',
            'invaders' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name
            ],
            'planet' => $this->planetArr[1]->name
        ]);
        $data = $response->json();
        $this->assertEquals(
            'Planet invaded successfully!',
            $data['message']);
        $this->assertDatabaseHas('invasions', [
            'title' => 'Test Invasion'
        ]);
        $response->assertStatus(200);
    }

    private function prepareDatabase(): void {
        $peopleArr = TestUtils::getResourceArray(People::factory()->count(3)->make());
        $this->peopleArr = $peopleArr;
        $this->peopleService->store($peopleArr);

        $planetArr = TestUtils::getResourceArray(Planet::factory()->count(2)->make());
        $this->planetArr = $planetArr;
        $this->planetService->store($planetArr);

        $starshipArr = TestUtils::getResourceArray(Starship::factory()->count(2)->make());
        $this->starshipArr = $starshipArr;
        $this->starshipService->store($starshipArr);
        $this->starshipService->attachToPerson($peopleArr[0]->name, $starshipArr[0]->name);

        $vehicleArr = TestUtils::getResourceArray(Vehicle::factory()->count(2)->make());
        $this->vehicleArr = $vehicleArr;
        $this->vehicleService->store($vehicleArr);
        $this->vehicleService->attachToPerson($peopleArr[1]->name, $vehicleArr[1]->name);

    }
}
