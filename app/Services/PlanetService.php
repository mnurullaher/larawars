<?php

namespace App\Services;

use App\Models\Planet;

class PlanetService {

    public function store(array $planets)
    {
        foreach ($planets as $planet) {
            Planet::updateOrCreate(['name' => $planet->name], get_object_vars($planet));
        }
    }
}