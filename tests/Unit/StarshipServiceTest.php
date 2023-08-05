<?php

namespace Tests\Unit;

use App\Client\ResourceClient;
use App\Services\StarshipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StarshipServiceTest extends TestCase
{
    use RefreshDatabase;
    private StarshipService $starshipService;

    public function __construct(string $name)
    {
        $this->starshipService = new StarshipService();
        parent::__construct($name);
    }

    public function test_create_and_update_properly(): void
    {
        $starshipsArr = ResourceClient::getResource('starships');
        $this->starshipService->store($starshipsArr);

        $this->assertDatabaseCount('starships', 36);
        $this->assertDatabaseHas('starships', [
            'name' => 'CR90 corvette',
            'model' => 'CR90 corvette',
            'manufacturer' => 'Corellian Engineering Corporation',
            'cost_in_credits' => '3500000',
            'length' => '150',
            'max_atmosphering_speed' => '950',
            'crew' => '30-165',
            'passengers' => '600',
            'cargo_capacity' => '3000000',
            'consumables' => '1 year',
            'hyperdrive_rating' => '2.0',
            'MGLT' => '60',
            'starship_class' => 'corvette'
        ]);

        $starshipsArr[0]->model = 'updated model';
        $this->starshipService->store($starshipsArr);

        $this->assertDatabaseCount('starships', 36);
        $this->assertDatabaseHas('starships', [
            'name' => 'CR90 corvette',
            'model' => 'updated model',
            'manufacturer' => 'Corellian Engineering Corporation',
            'cost_in_credits' => '3500000',
            'length' => '150',
            'max_atmosphering_speed' => '950',
            'crew' => '30-165',
            'passengers' => '600',
            'cargo_capacity' => '3000000',
            'consumables' => '1 year',
            'hyperdrive_rating' => '2.0',
            'MGLT' => '60',
            'starship_class' => 'corvette'
        ]);
    }
}
