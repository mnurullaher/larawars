<?php

namespace App\Http\Controllers;

use App\Services\StarshipService;
use Illuminate\Http\JsonResponse;

class StarshipController extends Controller
{
    public function __construct(private StarshipService $starshipService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            $this->starshipService->getALlStarships(),
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $starship = $this->starshipService->detail($id);
        if ($starship) {
            return response()->json([
                $starship,
            ]);
        }

        return response()->json([
            'error' => 'Not Found',
        ], 404);
    }
}
