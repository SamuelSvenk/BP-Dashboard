<?php
use App\Http\Controllers\BuildController;

Route::post('/builds', [BuildController::class, 'store']);
