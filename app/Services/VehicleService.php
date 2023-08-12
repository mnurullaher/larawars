<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Services\Interfaces\StorableResource;

class VehicleService implements StorableResource {


    public function __construct(private PeopleService $peopleService)
    {
    }

    public function store(array $vehicles): void
    {
        foreach ($vehicles as $vehicle) {
            Vehicle::updateOrCreate(['name' => $vehicle->name], json_decode(json_encode($vehicle), true));
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

    public function attachToPerson($personName, $vehicleName): void {
        $vehicle = Vehicle::where('name', $vehicleName)->first();
        $owner_id = $this->peopleService->getByName($personName)->id;
        $vehicle->owner_id = $owner_id;
        $vehicle->update();
    }
}
