<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommandesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'apiRegister']);

Route::middleware('auth:sanctum')->get('/user', [ClientController::class, 'getUserProfile']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [CommandesController::class, 'index']);
});

