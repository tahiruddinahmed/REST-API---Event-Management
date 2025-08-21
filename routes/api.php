<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class ,'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

// public route 
Route::apiResource('events', EventController::class)
    ->only(['index', 'show']);
// Public Route
Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped()    
    ->only(['index', 'show']);

// protected route 
Route::apiResource('events', EventController::class)
    ->only(['store', 'update', 'destroy'])->middleware('auth:sanctum');


// protected route 
Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped()->only(['store', 'destroy'])
    ->middleware('auth:sanctum');
