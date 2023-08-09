<?php

namespace App\Services;

use App\Models\People;
use App\Models\Starship;
use App\Services\Interfaces\ResourceService;

class StarshipService implements ResourceService {


    public function __construct(private PeopleService $peopleService)
    {
    }

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

    public function getByName($name) {
        return Starship::where('name', $name)->first();
    }

    public function attachToPerson($personName, $starshipName): void {
        $starship = Starship::where('name', $starshipName)->first();
        $owner_id = $this->peopleService->getByName($personName)->id;
        $starship->owner_id = $owner_id;
        $starship->update();
    }
}
