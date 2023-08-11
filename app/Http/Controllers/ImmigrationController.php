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
            'pilot' => 'required',
            'immigrants' => ['required', 'array'],
            'planet' => 'required'
        ]);

        $pilotName = $request->input('pilot');
        $immigrantNames = $request->input('immigrants');
        $planetName = $request->input('planet');
        $planet = Planet::where('name', $planetName)->first();

        $resourceValidation = $this->validateResources($immigrantNames, $planetName, $pilotName);

        if (!$resourceValidation['isValid']) {
            return response()->json([
                'message' => $resourceValidation['message']
            ], 400);
        }

        if (intval($planet->population) >= 2000000) {
            return response()->json([
                'message' => 'Go back to your homeland! ' . $planetName . ' can\'t accept more people!'
            ]);
        }

        foreach ($this->getImmigrants($immigrantNames) as $immigrant) {
            $immigrant->immigrated_planet_id = $planet->id;
            $immigrant->update();
        }

        $planet->population = intVal($planet->population) + count($immigrantNames);
        $planet->update();

        return response()->json([
            'message' => "Welcome to " . $planetName . '\'s generous lands!'
        ]);
    }

    private function validateResources($immigrantNames, $planetName, $pilotName): array {

        if (!People::where('name', $pilotName)->exists()) {
            return $this->resourceValidationError('You can not call a pilot from another universe!');
        }

        if (!$this->hasStarship($pilotName)) {
            return $this->resourceValidationError("Pilots need to have starships!");
        }

        foreach ($immigrantNames as $immigrantName) {
            if (!People::where('name', $immigrantName)->exists()) {
                return $this->resourceValidationError("Only people belongs to this universe can immigrate in this lands!");
            }
        }

        if(count(array_unique($immigrantNames)) != count($immigrantNames)) {
            return $this->resourceValidationError("Duplication in immigrant name");
        }

        foreach ($this->getImmigrants($immigrantNames) as $immigrant) {
            if ($immigrant->immigratedPlanet) {
                return $this->resourceValidationError($immigrant->name . " has already immigrated");
            }
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

    private function hasStarship($pilotName): bool {
        $pilot = People::where('name', $pilotName)->first();
        return !$pilot->starships->isEmpty();
    }
}
