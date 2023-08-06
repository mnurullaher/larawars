<?php

namespace App\Http\Controllers;

use App\Services\StarshipService;

class StarshipController extends Controller
{
    public function __construct(private StarshipService $starshipService){}

    public function index() {
        return $this->starshipService->getALlStarships();
    }

    public function detail(int $id) {
        $starship = $this->starshipService->detail($id);
        if ($starship) {
            return json_encode($starship);
        }
        return json_encode(['error' => 'Not Found']);
    }
}
