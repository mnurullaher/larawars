<?php

namespace App\Services;

use App\Models\Planet;
use App\Services\Interfaces\StorableResource;

class PlanetService implements StorableResource {

    public function store(array $planets)
    {
        foreach ($planets as $planet) {
            Planet::updateOrCreate(['name' => $planet->name], json_decode(json_encode($planet), true));
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
