<?php

namespace App\Http\Controllers;

use App\Services\PeopleService;

class PeopleController extends Controller
{
    public function __construct(private PeopleService $peopleService){}

    public function index() {
        return json_encode($this->peopleService->getAllPeople());
    }

    public function detail(int $id) {
        $person = $this->peopleService->detail($id);
        if ($person) {
            return json_encode($person);
        }
        return json_encode(['error' => 'Not Found']);
    }
}
