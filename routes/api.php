<?php

use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PlanetController;
use App\Http\Controllers\StarshipController;
use App\Http\Controllers\VehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/people/index', [PeopleController::class, 'index']);
Route::get('/people/{id}', [PeopleController::class, 'detail']);

Route::get('/planets/index', [PlanetController::class, 'index']);
Route::get('/planets/{id}', [PlanetController::class, 'detail']);

Route::get('/starships/index', [StarshipController::class, 'index']);
Route::get('/starships/{id}', [StarshipController::class, 'detail']);

Route::get('/vehicles/index', [VehicleController::class, 'index']);
Route::get('/vehicles/{id}', [VehicleController::class, 'detail']);
