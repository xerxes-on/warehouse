<?php

use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', ProductController::class);
    Route::get('products/search', [ProductController::class, 'searchProduct'])->name('products.search');

    Route::middleware('admin')->controller(ShipmentController::class)->group(function () {
        Route::get('shipment', 'index')->name('shipment.index');
        Route::get('shipment/{shipment}', 'show')->name('shipment.show');
        Route::put('shipment/{shipment}', 'update')->name('shipment.update');

        Route::get('shipments/delivering', 'showDelivering')->name('shipment.delivering');
        Route::get('shipments/delivered',  'showDelivered')->name('shipment.delivered');
        Route::get('shipments/returned',  'showReturned')->name('shipment.returned');
    });

    Route::controller(OrderItemController::class)->group(function () {
        Route::post('add', 'add')->name('cart.add');
        Route::post('order/remove-items', 'removeItems')->name('cart.remove-items');
        Route::post('order/updated-items', 'updateItems')->name('cart.update-items');
        Route::get('/order/{order}/refresh-details', 'refreshOrderDetails')
            ->name('orders.refresh-details');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('orders', 'index')->name('orders.index');
        Route::get('orders/{order}', 'show')->name('orders.show');
        Route::put('orders/{order}', 'update')->name('orders.update');

        Route::get('delivered/orders', 'showDelivered')->name('orders.delivered');
        Route::get('ordered/orders',  'showOrdered')->name('orders.ordered');

    });
});
require __DIR__ . '/auth.php';
