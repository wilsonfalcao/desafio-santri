<?php

declare(strict_types=1);

use App\Http\Controllers\CalculateController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\MesureResponseTime;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Products Route
Route::apiResource('products', ProductController::class);

Route::middleware([MesureResponseTime::class])->group(function () {
    // Calculate Route
    Route::post('calculate', [CalculateController::class, 'calculate']);
});
