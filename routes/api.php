<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExplorationController;
use App\Http\Controllers\ImmigrationController;
use App\Http\Controllers\InvasionController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/people', [PeopleController::class, 'index']);
    Route::get('/people/{id}', [PeopleController::class, 'detail']);

    Route::get('/planets', [PlanetController::class, 'index']);
    Route::get('/planets/{id}', [PlanetController::class, 'detail']);

    Route::get('/starships', [StarshipController::class, 'index']);
    Route::get('/starships/{id}', [StarshipController::class, 'detail']);

    Route::get('/vehicles', [VehicleController::class, 'index']);
    Route::get('/vehicles/{id}', [VehicleController::class, 'detail']);

    Route::post('/invade', [InvasionController::class, 'invade']);
    Route::post('/explore', [ExplorationController::class, 'explore']);
    Route::post('/immigrate', [ImmigrationController::class, 'immigrate']);
});
