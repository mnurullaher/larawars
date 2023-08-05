<?php

namespace Tests\Feature;

use App\Client\ResourceClient;
use App\Services\PeopleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PeopleControllerTest extends TestCase
{
    use RefreshDatabase;
    private PeopleService $peopleService;
//    public function __construct(string $name)
//    {
//        parent::__construct($name);
//        $this->peopleService = new PeopleService();
//    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->peopleService = new PeopleService();
        $this->peopleService->store(ResourceClient::getResource('people'));
    }

    public function test_should_return_all_people(): void
    {
        $response = $this->get('/api/people/index');
        $data = $response->json();

        $response->assertStatus(200);
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(82, $data['total']);

        Artisan::call('migrate:refresh');
    }

    public function test_should_return_one_person(): void
    {
        $response = $this->get('/api/people/1');
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
