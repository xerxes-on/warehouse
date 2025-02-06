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
    }
}
