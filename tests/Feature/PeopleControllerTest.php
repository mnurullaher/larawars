<?php

namespace Tests\Feature;

use App\Client\ResourceClient;
use App\Models\User;
use App\Services\PeopleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\TestUtils;

class PeopleControllerTest extends TestCase
{
    use RefreshDatabase;
    private PeopleService $peopleService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->peopleService = new PeopleService();
        $this->peopleService->store(ResourceClient::getResource('people'));
        $this->user = TestUtils::createUser();
    }

    public function test_should_return_all_people(): void
    {
        $response = $this->actingAs($this->user)->get('/api/people/index');
        $data = $response->json();

        $response->assertStatus(200);
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(82, $data['total']);

        Artisan::call('migrate:refresh');
    }

    public function test_should_return_one_person(): void
    {
        $response = $this->actingAs($this->user)->get('/api/people/1');
        $data = $response->json();
        $notFoundResponse = $this->get('/api/people/99');
        $notFoundData = $notFoundResponse->json();

        $response->assertStatus(200);
        $notFoundResponse->assertStatus(200);
        $this->assertEquals('Luke Skywalker', $data['name']);
        $this->assertEquals('Not Found', $notFoundData['error']);

        Artisan::call('migrate:refresh');
    }
}
