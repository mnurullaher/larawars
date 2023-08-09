<?php

namespace App\Http\Controllers;

use App\Services\VehicleService;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function __construct(private VehicleService $vehicleService){}

    public function index(): JsonResponse
    {
        return response()->json([
            $this->vehicleService->getALlVehicles()
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $vehicle = $this->vehicleService->detail($id);
        if ($vehicle) {
            return response()->json([
                $vehicle
            ]);
        }
        return response()->json([
            'error' => 'Not Found'
        ], 404);
    }
}
