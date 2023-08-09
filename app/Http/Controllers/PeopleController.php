<?php

namespace App\Http\Controllers;

use App\Services\PeopleService;
use Illuminate\Http\JsonResponse;

class PeopleController extends Controller
{
    public function __construct(private PeopleService $peopleService){}

    public function index(): JsonResponse
    {
        return response()->json([
            $this->peopleService->getAllPeople()
        ]);
    }

    public function detail(int $id): JsonResponse {
        $person = $this->peopleService->detail($id);
        if ($person) {
            return response()->json([
                $person
            ]);
        }
        return response()->json([
            'error' => 'Not Found'
        ], 404);
    }
}
