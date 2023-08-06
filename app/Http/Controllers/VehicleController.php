<?php

namespace App\Http\Controllers;

use App\Services\VehicleService;

class VehicleController extends Controller
{
    public function __construct(private VehicleService $vehicleService){}

    public function index() {
        return $this->vehicleService->getALlVehicles();
    }

    public function detail(int $id) {
        $vehicle = $this->vehicleService->detail($id);
        if ($vehicle) {
            return json_encode($vehicle);
        }
        return json_encode(['error' => 'Not Found']);
    }
}
