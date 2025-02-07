<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function index()
    {
        $allWarehouseProducts = collect();
        $warehouses = Warehouse::with('warehouseProducts')->get();
        foreach ($warehouses as $warehouse) {
            $allWarehouseProducts = $allWarehouseProducts->merge($warehouse->warehouseProducts);
        }

        $productIds = $allWarehouseProducts->pluck('id')->toArray();

        return Cache::remember('products', 60, function () use ($productIds) {
            return Product::whereIn('id', $productIds)->paginate(12);
        });
    }

    public function update(Request $request, Product $product): Product
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'price' => 'required|numeric',
        ]);
        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);

        return $product;
    }

    public function store(Request $request): Product
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'price' => 'required|numeric',
        ]);
        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);

        return $product;
    }

    public function indexForAdmin(Warehouse $warehouse)
    {
        $products = $warehouse->warehouseProducts()->get();

        return Cache::remember('products', 60, function () use ($products) {
            return $products->paginate(20);
        });
    }
}
