<?php

namespace App\Services;

use App\Models\Planet;
use App\Services\Interfaces\ResourceService;

class PlanetService implements ResourceService {

    public function store(array $planets)
    {
        foreach ($planets as $planet) {
            Planet::updateOrCreate(['name' => $planet->name], get_object_vars($planet));
        }
    }

    public function getALlPlanets()
    {
        return Planet::paginate(10);
    }

    public function detail(int $id)
    {
        return Planet::find($id);
    }
}
