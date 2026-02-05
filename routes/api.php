<?php

declare(strict_types=1);

use App\Http\Controllers\CalculateController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Products Route
Route::apiResource('products', ProductController::class);

// Calculate Route
Route::apiResource('calculate', CalculateController::class);
