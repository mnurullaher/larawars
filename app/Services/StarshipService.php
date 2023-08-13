<?php

namespace App\Services;

use App\Models\Starship;
use App\Services\Interfaces\StorableResource;

class StarshipService implements StorableResource {


    public function __construct(private PeopleService $peopleService)
    {
    }

    public function store(array $starships): void
    {
        foreach ($starships as $starship) {
            Starship::updateOrCreate(['name' => $starship->name], json_decode(json_encode($starship), true));
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

    public function getByName($name) {
        return Starship::where('name', $name)->first();
    }

    public function attachToPerson($personName, $starshipName): void {
        $starship = $this->getByName($starshipName);
        $owner_id = $this->peopleService->getByName($personName)->id;
        $starship->owner_id = $owner_id;
        $starship->update();
    }
}
