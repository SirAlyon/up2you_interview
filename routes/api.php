<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\ParticipationController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth.apikey')->group(function () {
    Route::apiResource('events', EventController::class);
    Route::apiResource('attendees', AttendeeController::class);
    Route::post('events/{event}/register', [ParticipationController::class, 'register']);
});

