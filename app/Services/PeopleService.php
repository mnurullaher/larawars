<?php

namespace App\Services;

use App\Models\People;
use App\Services\Interfaces\ResourceService;

class PeopleService implements ResourceService {

    public function store(array $people)
    {
        foreach ($people as $person) {
            People::updateOrCreate(['name' => $person->name], get_object_vars($person));
        }
    }

    public function getAllPeople()
    {
        return People::paginate(10);
    }

    public function detail(int $id)
    {
        return People::find($id);
    }

    public function getByName($name) {
        return People::where('name', $name)->first();
    }
}
