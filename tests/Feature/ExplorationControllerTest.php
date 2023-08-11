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

class ExplorationControllerTest extends TestCase
{
    use RefreshDatabase;

    private PeopleService $peopleService;
    private PlanetService $planetService;
    private StarshipService $starshipService;
    private array $peopleArr;
    private array $planetArr;
    private array $starshipArr;

    protected function setUp(): void
    {
        parent::setUp();
        $this->peopleService = new PeopleService();
        $this->planetService = new PlanetService();
        $this->starshipService = new StarshipService($this->peopleService);
        $this->prepareDatabase();
    }

    public function test_return_400_for_invalid_explorers(): void
    {
        $response = $this->post('/api/explore', [
            'explorers' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name, 'Gandalf'
            ],
            'planet' => $this->planetArr[0]->name
        ]);
        $data = $response->json();

        $this->assertEquals('Not this time. You may have Gandalf in another universe!', $data['message']);
        $response->assertStatus(400);
    }

    public function test_return_400_for_explorer_duplication() {
        $response = $this->post('/api/explore', [
            'explorers' => [
                $this->peopleArr[0]->name, $this->peopleArr[0]->name
            ],
            'planet' => $this->planetArr[0]->name
        ]);
        $data = $response->json();

        $this->assertEquals('Explorers cannot clone themselves yet!', $data['message']);
        $response->assertStatus(400);
    }

    public function test_return_400_for_lack_of_starships() {
        $response = $this->post('/api/explore', [
            'explorers' => [
                $this->peopleArr[1]->name
            ],
            'planet' => $this->planetArr[0]->name
        ]);
        $data = $response->json();

        $this->assertEquals(
            'This exploration is not possible!. Those explorers don\'t have necessary equipments.',
            $data['message']);
        $response->assertStatus(400);
    }

    public function test_return_400_for_non_existed_planets() {
        $response = $this->post('/api/explore', [
            'explorers' => [
                $this->peopleArr[0]->name,
            ],
            'planet' => 'Middle Earth'
        ]);
        $data = $response->json();

        $this->assertEquals(
            'Non-existed planets cannot be visited for explorations!',
            $data['message']);
        $response->assertStatus(400);
    }

    public function test_explorers_gain_sense_force_ability_with_valid_requests() {
        $response = $this->post('/api/explore', [
            'explorers' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name
            ],
            'planet' => $this->planetArr[0]->name
        ]);
        $data = $response->json();

        $this->assertEquals(
            'Explorers now can sense force!',
            $data['message']);
        $this->assertEquals(true, $this->peopleService->getByName($this->peopleArr[0]->name)->sense_force);
        $response->assertStatus(200);
    }

    public function test_explorers_gain_nothing_after_valid_requests_with_forceless_planets() {
        $response = $this->post('/api/explore', [
            'explorers' => [
                $this->peopleArr[0]->name, $this->peopleArr[1]->name
            ],
            'planet' => $this->planetArr[1]->name
        ]);
        $data = $response->json();

        $response->assertStatus(200);
        $this->assertEquals(
            'Exploration completed but nothing has founded',
            $data['message']);
    }
    private function prepareDatabase() {
        $peopleArr = TestUtils::getResourceArray(People::factory()->count(3)->make());
        $this->peopleArr = $peopleArr;
        $this->peopleService->store($peopleArr);

        $planetArr = TestUtils::getResourceArray(Planet::factory()->count(2)->make());
        $this->planetArr = $planetArr;
        $this->planetService->store($planetArr);
        $this->planetService->equipWithForce([$planetArr[0]->name]);

        $starshipArr = TestUtils::getResourceArray(Starship::factory()->count(2)->make());
        $this->starshipArr = $starshipArr;
        $this->starshipService->store($starshipArr);
        $this->starshipService->attachToPerson($peopleArr[0]->name, $starshipArr[0]->name);
    }
}
