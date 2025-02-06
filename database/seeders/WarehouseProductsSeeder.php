<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Warehouse::all() as $warehouse) {
            $randomProducts = Product::inRandomOrder()->take(200)->get();
            $insertData = [];
            foreach ($randomProducts as $product) {
                $insertData[] = [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'amount' => rand(1, 450),
                ];
            }
            ProductWarehouse::insert($insertData);
        }
    }
}
