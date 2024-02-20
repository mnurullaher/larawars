<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Planet>
 */
class PlanetFactory extends Factory
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
            'rotation_period' => 'period_r',
            'orbital_period' => 'period_o',
            'diameter' => 'diamater',
            'climate' => 'climate',
            'gravity' => 'gravity',
            'terrain' => 'terrain',
            'surface_water' => 'water',
            'population' => '100000',
            'has_force' => false,
        ];
    }
}
