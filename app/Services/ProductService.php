<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function index()
    {
        return Cache::remember('products', 60, function () {
            return Product::paginate(10);
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
            'price' => $validated['price']
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
            'price' => $validated['price']
        ]);
        return $product;
    }
}
