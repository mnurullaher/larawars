<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Models\Planet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        return response()->json('go ahead');
    }


    private function validateResources($explorerNames, $planetName): array {
        if(count(array_unique($explorerNames)) != count($explorerNames)) {
            return $this->resourceValidationError("No one can go more than one expedition at the same time");
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
        $explorer = array();
        foreach ($explorerNames as $explorerName) {
            $explorer[] = People::where('name', $explorerName)->first();
        }
        return $explorer;
    }
    private function hasStarship($explorerNames): bool {
        foreach ($this->getExplorers($explorerNames) as $explorer) {
            if (count($explorer->starships) > 0) {
                return true;
            }
        }
        return false;
    }

}
