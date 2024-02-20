<?php

namespace App\Http\Controllers;

use App\Services\PlanetService;
use Illuminate\Http\JsonResponse;

class PlanetController extends Controller
{
    public function __construct(private PlanetService $planetService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            $this->planetService->getALlPlanets(),
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $planet = $this->planetService->detail($id);
        if ($planet) {
            return response()->json([
                $planet,
            ]);
        }

        return response()->json([
            'error' => 'Not Found',
        ], 404);
    }
}
