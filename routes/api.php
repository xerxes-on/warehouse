<?php

use App\Http\Controllers\Api\Auth\AuthenticateUserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthenticateUserController::class, 'register']);
Route::post('login', [AuthenticateUserController::class, 'login']);
Route::post('/forgot-password', [AuthenticateUserController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthenticateUserController::class, 'logout']);
    Route::put('/update-password', [AuthenticateUserController::class, 'updatePassword']);

    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index');
        Route::get('products/{product}', 'show');
        Route::get('search/products', 'searchProduct');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('orders', 'index');
        Route::get('orders/{order}', 'show');
        Route::put('orders/{order}', 'update');
        Route::get('cart', 'cart');
    });
    Route::controller(OrderItemController::class)->group(function () {
        Route::post('add', 'add');
        Route::post('order/remove-items', 'removeItems');
        Route::post('order/updated-items', 'updateItems');
        Route::get('/order/{order}/refresh-details', 'refreshOrderDetails');
    });

});



