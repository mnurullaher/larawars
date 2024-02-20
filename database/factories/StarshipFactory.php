<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Starship>
 */
class StarshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'model' => 'model',
            'manufacturer' => 'manufacturer',
            'cost_in_credits' => 'cost',
            'length' => 'length',
            'max_atmosphering_speed' => 'speed',
            'crew' => 'crew',
            'passengers' => 'passengers',
            'cargo_capacity' => 'capacity',
            'consumables' => 'consumable',
            'hyperdrive_rating' => 'rating',
            'MGLT' => 'mglt',
            'starship_class' => 'class',
            'owner_id' => null,
        ];
    }
}
