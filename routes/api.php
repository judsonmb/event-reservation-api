<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events/{event}/reserve', [ReservationController::class, 'store']);
    Route::post('/events/{event}/reviews', [ReviewController::class, 'store']);
    Route::get('/events/{event}/reviews', [ReviewController::class, 'index']);
});
