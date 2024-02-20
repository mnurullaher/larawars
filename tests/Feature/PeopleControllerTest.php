<?php

namespace Tests\Feature;

use App\Models\People;
use App\Models\User;
use App\Services\PeopleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\TestUtils;

class PeopleControllerTest extends TestCase
{
    use RefreshDatabase;

    private PeopleService $peopleService;

    private User $user;

    private array $peopleArr = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->peopleService = new PeopleService();
        $people = People::factory()->count(20)->make();
        $this->peopleArr = TestUtils::getResourceArray($people);
        $this->user = TestUtils::createUser();
    }

    public function test_should_return_all_people(): void
    {
        $this->peopleService->store($this->peopleArr);

        $response = $this->actingAs($this->user)->get('/api/people');
        $data = $response->json()[0];

        $response->assertStatus(200);
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(20, $data['total']);
        Artisan::call('migrate:refresh');
    }

    public function test_should_return_one_person(): void
    {
        $requestedId = count($this->peopleArr) - 1;
        $this->peopleService->store($this->peopleArr);

        $response = $this->actingAs($this->user)->get('/api/people/'.$requestedId);
        $data = $response->json()[0];

        $response->assertStatus(200);
        $this->assertEquals($this->peopleArr[$requestedId - 1]->name, $data['name']);

        Artisan::call('migrate:refresh');
    }

    public function test_should_return_not_found_for_non_existed_person(): void
    {
        $this->peopleService->store($this->peopleArr);

        $response = $this->actingAs($this->user)->get('/api/people/'.count($this->peopleArr) + 1);
        $data = $response->json();

        $response->assertStatus(404);
        $this->assertEquals('Not Found', $data['error']);

        Artisan::call('migrate:refresh');
    }
}
