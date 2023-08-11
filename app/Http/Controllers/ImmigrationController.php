<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Models\Planet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImmigrationController extends Controller
{
    public function immigrate(Request $request): JsonResponse {
        $request->validate([
            'immigrants' => ['required', 'array'],
            'planet' => 'required'
        ]);

        $immigrantNames = $request->input('immigrants');
        $planetName = $request->input('planet');

        $resourceValidation = $this->validateResources($immigrantNames, $planetName);

        if (!$resourceValidation['isValid']) {
            return response()->json([
                'message' => $resourceValidation['message']
            ], 400);
        }

        return response()->json([
            'message' => "Go ahead"
        ]);
    }

    private function validateResources($immigrantNames, $planetName): array {
        if(count(array_unique($immigrantNames)) != count($immigrantNames)) {
            return $this->resourceValidationError("Duplication in immigrant name");
        }

        foreach ($immigrantNames as $immigrantName) {
            if (!People::where('name', $immigrantName)->exists()) {
                return $this->resourceValidationError("Only people belongs to this universe can immigrate in this lands!");
            }
        }
        if (!$this->hasStarship($immigrantNames)) {
            return $this->resourceValidationError("At least one of immigrants need to have a starship!");
        }
        if (!Planet::where('name', $planetName)->exists()) {
            return $this->resourceValidationError("Immigrate to non-existed planets is impossible");
        }

        return ['isValid' => true];
    }

    private function resourceValidationError($message): array {
        return [
            'isValid' => false,
            'message' => $message,
        ];
    }

    private function getImmigrants($immigrantNames): array {
        $immigrants = array();
        foreach ($immigrantNames as $immigrantName) {
            $immigrants[] = People::where('name', $immigrantName)->first();
        }
        return $immigrants;
    }

    private function hasStarship($immigrantNames): bool {
        foreach ($this->getImmigrants($immigrantNames) as $immigrant) {
            if (!$immigrant->starships->isEmpty()) {
                return true;
            }
        }
        return false;
    }
}
