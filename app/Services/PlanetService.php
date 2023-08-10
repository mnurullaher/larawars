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

    public function getByName($name) {
        return Planet::where('name', $name)->first();
    }

    public function equipWithForce($planetNames): void {
        foreach ($planetNames as $planetName) {
            $planet = Planet::where('name', $planetName)->first();
            $planet->has_force = true;
            $planet->update();
        }
    }
}
