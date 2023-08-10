<?php

namespace App\Http\Controllers;

use App\Models\Invasion;
use App\Models\People;
use App\Models\Planet;
use Illuminate\Http\Request;

class InvasionController extends Controller
{
    public function invade(Request $request) {
        $request->validate([
            'invaders' => ['required', 'array'],
            'planet' => 'required',
            'title' => 'required'
        ]);

        $title = $request->input('title');
        $invaderNames = $request->input('invaders');
        $planetName = $request->input('planet');
        $resourceValidation = $this->validateResources($invaderNames, $planetName);

        if (!$resourceValidation['isValid']) {
            return response()->json([
                'message' => $resourceValidation['message']
            ], 400);
        }

        $invasion = Invasion::create([
            'title' => $title,
            'planet_id' => Planet::where('name', $planetName)->first()->id
        ]);

        foreach ($this->getInvaders($invaderNames) as $invader) {
            $invasion->people()->attach($invader);
        }

        return response()->json([
            'message' => 'Planet invaded successfully!'
        ]);
    }

    private function validateResources($invaderNames, $planetName): array {
        if(count(array_unique($invaderNames)) != count($invaderNames)) {
            return $this->resourceValidationError("You can not call a person twice for an invasion!");
        }
        if (count($invaderNames) < 2) {
            return $this->resourceValidationError("You can not invade a planet with a handful of people!");
        }
        foreach ($invaderNames as $invaderName) {
            if (!People::where('name', $invaderName)->exists()) {
                return $this->resourceValidationError("Not this time. You may have $invaderName in another universe!");
            }
        }
        if (!($this->hasStarship($invaderNames) && $this->hasVehicle($invaderNames))) {
            return $this->resourceValidationError("This expedition is not possible!. Those invaders don't have necessary equipments.");
        }
        if (!Planet::where('name', $planetName)->exists()) {
            return $this->resourceValidationError("Non-existed planets cannot be invaded");
        }
        if (Planet::where('name', $planetName)->first()->invasion) {
            return $this->resourceValidationError('Planet has already invaded');
        }

        return ['isValid' => true];
    }

    private function resourceValidationError($message): array {
        return [
            'isValid' => false,
            'message' => $message,
        ];
    }

    private function getInvaders($invaderNames): array {
        $invaders = array();
        foreach ($invaderNames as $invaderName) {
            $invaders[] = People::where('name', $invaderName)->first();
        }
        return $invaders;
    }

    private function hasStarship($invaderNames): bool {
        foreach ($this->getInvaders($invaderNames) as $invader) {
            if (count($invader->starships) > 0) {
                return true;
            }
        }
        return false;
    }

    private function hasVehicle($invaderNames): bool {
        foreach ($this->getInvaders($invaderNames) as $invader) {
            if (count($invader->vehicles) > 0) {
                return true;
            }
        }
        return false;
    }
}
