<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OutfitController;
use App\Http\Controllers\Api\WardrobeItemController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('outfits', OutfitController::class);
    Route::apiResource('wardrobe-items', WardrobeItemController::class);
});