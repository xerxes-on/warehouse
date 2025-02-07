<?php

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

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
//
//Route::middleware('auth:sanctum')->group(function () {
    Route::get('products/search', [ProductController::class, 'searchProduct'])->name('products.search');
//});

