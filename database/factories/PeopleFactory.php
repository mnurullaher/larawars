<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\People>
 */
class PeopleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'height' => 'height',
            'mass' => 'mass',
            'hair_color' => 'color',
            'skin_color' => 'color',
            'eye_color' => 'color',
            'birth_year' => '1BBB23',
            'gender' => $this->faker->randomElement(['male', 'female']),
            'immigrated_planet_id' => null,
        ];
    }
}
