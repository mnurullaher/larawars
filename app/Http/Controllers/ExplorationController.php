<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Models\Planet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExplorationController extends Controller
{
    public function explore(Request $request): JsonResponse
    {
        $request->validate([
            'explorers' => ['required', 'array'],
            'planet' => 'required'
        ]);

        $explorerNames = $request->input('explorers');
        $planetName = $request->input('planet');

        $resourceValidation = $this->validateResources($explorerNames, $planetName);

        if (!$resourceValidation['isValid']) {
            return response()->json([
                'message' => $resourceValidation['message']
            ], 400);
        }

        if (Planet::where('name', $planetName)->first()->has_force) {
            foreach ($this->getExplorers($explorerNames) as $explorer) {
                $explorer->sense_force = true;
                $explorer->update();
            }

            return response()->json([
                'message' => "Explorers now can sense force!"
            ]);
        }

        return response()->json([
            'message' => "Exploration completed but nothing has founded"
        ]);
    }


    private function validateResources($explorerNames, $planetName): array {
        if(count(array_unique($explorerNames)) != count($explorerNames)) {
            return $this->resourceValidationError("Explorers cannot clone themselves yet!");
        }

        foreach ($explorerNames as $explorerName) {
            if (!People::where('name', $explorerName)->exists()) {
                return $this->resourceValidationError("Not this time. You may have $explorerName in another universe!");
            }
        }
        if (!$this->hasStarship($explorerNames)) {
            return $this->resourceValidationError("This exploration is not possible!. Those explorers don't have necessary equipments.");
        }
        if (!Planet::where('name', $planetName)->exists()) {
            return $this->resourceValidationError("Non-existed planets cannot be visited for explorations!");
        }

        return ['isValid' => true];
    }

    private function resourceValidationError($message): array {
        return [
            'isValid' => false,
            'message' => $message,
        ];
    }
    private function getExplorers($explorerNames): array {
        $explorers = array();
        foreach ($explorerNames as $explorerName) {
            $explorers[] = People::where('name', $explorerName)->first();
        }
        return $explorers;
    }
    private function hasStarship($explorerNames): bool {
        foreach ($this->getExplorers($explorerNames) as $explorer) {
            if (!$explorer->starships->isEmpty()) {
                return true;
            }
        }
        return false;
    }

}
