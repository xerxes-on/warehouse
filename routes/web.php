<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\OrderItemController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ShipmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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

    Route::middleware('admin')->group(function () {
        Route::resource('shipment', ShipmentController::class);
        Route::get('shipments/delivering', [ShipmentController::class, 'showDelivering'])->name('shipment.delivering');
        Route::get('shipments/delivered', [ShipmentController::class, 'showDelivered'])->name('shipment.delivered');
        Route::get('shipments/returned', [ShipmentController::class, 'showReturned'])->name('shipment.returned');

    });

    Route::post('add', [OrderItemController::class, 'add'])->name('cart.add');
    Route::post('order/remove-items', [OrderItemController::class, 'removeItems'])->name('cart.remove-items');
    Route::post('order/updated-items', [OrderItemController::class, 'updateItems'])->name('cart.update-items');
    Route::get('/order/{order}/refresh-details', [OrderItemController::class, 'refreshOrderDetails'])
        ->name('orders.refresh-details');

    Route::resource('orders', OrderController::class);
    Route::get('ordered/orders', [OrderController::class, 'showOrdered'])->name('orders.ordered');
    Route::get('delivered/orders', [OrderController::class, 'showDelivered'])->name('orders.delivered');
});
require __DIR__.'/auth.php';
