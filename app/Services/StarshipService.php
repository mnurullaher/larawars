<?php

namespace App\Services;

use App\Models\Starship;
use App\Services\Interfaces\ResourceService;

class StarshipService implements ResourceService {

    public function store(array $starships)
    {
        foreach ($starships as $starship) {
            Starship::updateOrCreate(['name' => $starship->name], get_object_vars($starship));
        }
    }

    public function getALlStarships()
    {
        return Starship::paginate(10);
    }

    public function detail(int $id)
    {
        return Starship::find($id);
    }
}
