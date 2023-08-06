<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Services\Interfaces\ResourceService;

class VehicleService implements ResourceService {

    public function store(array $vehicles)
    {
        foreach ($vehicles as $vehicle) {
            Vehicle::updateOrCreate(['name' => $vehicle->name], get_object_vars($vehicle));
        }
    }

    public function getALlVehicles()
    {
        return Vehicle::paginate(10);
    }

    public function detail(int $id)
    {
        return Vehicle::find($id);
    }
}
