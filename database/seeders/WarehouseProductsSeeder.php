<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseProductsSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = Warehouse::all();

        foreach (Product::all() as $product) {
            $randomWarehouse = $warehouses->random();
            ProductWarehouse::create([
                'product_id'   => $product->id,
                'warehouse_id' => $randomWarehouse->id,
                'amount'       => rand(1, 450),
            ]);
        }
    }
}
