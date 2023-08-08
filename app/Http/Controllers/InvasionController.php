<?php

namespace App\Http\Controllers;

use App\Models\People;
use App\Models\Planet;
use Illuminate\Http\Request;

class InvasionController extends Controller
{
    public function invade(Request $request) {
        $request->validate([
            'invaders' => ['required', 'array'],
            'planet' => 'required'
        ]);

        $invaders = $request->input('invaders');
        $planet = $request->input('planet');
        $resourceValidation = $this->validateResources($invaders, $planet);

        if (!$resourceValidation['isValid']) {
            return response()->json([
                'message' => $resourceValidation['message']
            ], 400);
        }

        return response()->json([
           'message' => "Now, check whether our people are ready for invasion or not!"
        ]);
    }

    private function validateResources($invaders, $planet): array {
        if(count(array_unique($invaders)) != count($invaders)) {
            return $this->resourceValidationError("You can not call a person twice for an invasion!");
        }
        if (count($invaders) < 2) {
            return $this->resourceValidationError("You can not invade a planet with a handful of people!");
        }
        foreach ($invaders as $invader) {
            if (!People::where('name', $invader)->exists()) {
                return $this->resourceValidationError("Not this time. You may have $invader in another universe!");
            }
        }
        if (!Planet::where('name', $planet)->exists()) {
            return $this->resourceValidationError("Non-existed planets cannot be invaded");
        }

        return ['isValid' => true];
    }

    private function resourceValidationError($message): array {
        return [
            'isValid' => false,
            'message' => $message,
        ];
    }
}
