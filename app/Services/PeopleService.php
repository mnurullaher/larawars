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
}
