<?php

namespace App\Http\Controllers;

use App\Services\PlanetService;

class PlanetController extends Controller
{
    public function __construct(private PlanetService $planetService){}

    public function index() {
        return $this->planetService->getALlPlanets();
    }

    public function detail(int $id) {
        $planet = $this->planetService->detail($id);
        if ($planet) {
            return json_encode($planet);
        }
        return json_encode(['error' => 'Not Found']);
    }
}
