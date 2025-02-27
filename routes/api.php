<?php

use App\Http\Controllers\Api\Auth\AuthenticateUserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShipmentController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthenticateUserController::class)->group(function (){
    Route::post('login',  'login');
    Route::post('register',  'register');
    Route::post('forgot-password',  'resetPassword');
});
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthenticateUserController::class, 'logout']);
    Route::put('/update-password', [AuthenticateUserController::class, 'updatePassword']);

    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index');
        Route::get('products/{product}', 'show');
        Route::get('search/products', 'searchProduct');
        Route::post('products', 'create');
        Route::put('products/{product}', 'update');
        Route::delete('products/{product}', 'destroy');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::post('orders', 'index');
        Route::get('orders/{order}', 'show');
        Route::put('orders/{order}', 'update');
        Route::get('cart', 'cart');
        Route::get('branches', 'getBranches');
        Route::get('calculations/{order}', 'calculations');
    });
    Route::controller(OrderItemController::class)->group(function () {
        Route::post('add', 'add');
        Route::post('order/remove-items', 'removeItems');
        Route::post('order/updated-items', 'updateItems');
        Route::get('/order/{order}/refresh-details', 'refreshOrderDetails');
    });
    Route::controller(ShipmentController::class)->group(function (){
        Route::get('shipments/{shipment}', 'show');
        Route::post('shipments', 'index');
        Route::patch('/shipment/update/{shipment}', 'update');
    });
});
Route::middleware('guest')->group(function () {
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});


