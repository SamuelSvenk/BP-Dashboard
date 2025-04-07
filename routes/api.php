<?php

use App\Http\Controllers\BuildController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Build notification endpoint
Route::post('/builds', [BuildController::class, 'store']);