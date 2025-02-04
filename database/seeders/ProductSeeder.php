<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(1000)->create();
//        $products = Product::all();
//        $warehouses = Warehouse::all();
//        $orders = Order::all();
//
//        // Fill product_warehouse pivot table
//        $products->each(function ($product) use ($warehouses) {
//            $product->warehouses()->attach(
//                $warehouses->random(rand(1, 3))->pluck('id')->toArray(),
//                ['quantity' => rand(0, 1000)]
//            );
//        });
//
//        // Fill order_items pivot table
//        $orders->each(function ($order) use ($products) {
//            $order->products()->attach(
//                $products->random(rand(1, 5))->pluck('id')->toArray(),
//                ['quantity' => rand(1, 10), 'price' => rand(10, 1000)]
//            );
//        });
    }
}
