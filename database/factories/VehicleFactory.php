<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
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
            'consumables' => 'consumable',
            'vehicle_class' => 'class',
            'owner_id' => null
        ];
    }
}
