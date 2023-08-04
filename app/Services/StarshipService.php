<?php

namespace App\Services;

use App\Models\Starship;

class StarshipService {

    public function store(array $starships)
    {
        foreach ($starships as $starship) {
            Starship::updateOrCreate(['name' => $starship->name], get_object_vars($starship));
        }
    }
}