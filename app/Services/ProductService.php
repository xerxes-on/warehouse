<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Requests\Products\SearchProductsRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductWarehouse;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function index()
    {
        $warehouseProducts = ProductWarehouse::where('amount', '>', 0)->get();
        $productQuantities = $warehouseProducts->groupBy('product_id')->map(function ($group) {
            return $group->sum('amount');
        });
        $productIds = $productQuantities->keys()->toArray();
        $products = Product::whereIn('id', $productIds)->paginate(12);
        $products->getCollection()->transform(function ($product) use ($productQuantities) {
            $product->quantity_left = $productQuantities[$product->id] ?? 0;
            return $product;
        });
        return $products;
    }

    public function show(Product $product)
    {
        $productId = 123;

        $totalQuantity = ProductWarehouse::where('product_id', $productId)
            ->where('amount', '>', 0)
            ->sum('amount');

        if ($totalQuantity > 0) {
            $product = Product::find($productId);

            if (!$product) {
                $product->quantity_left = $totalQuantity;
                return $product;
            }


        } else {
            // Handle the case where the product is not found in the warehouse (no stock)
            return response()->json(['message' => 'Product not found in warehouse or out of stock'], 404); // Or return null, or an appropriate message
        }
    }

    public function update(CreateProductRequest $request, Product $product): Product
    {
        $validated = $request->validated();
        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);

        return $product;
    }

    public function store(CreateProductRequest $request): Product
    {
        $validated = $request->validated();
        return Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
        ]);
    }

    /**
     * @throws Exception
     */
    public static function allocateProducts(Order $order): array
    {
        $allocations = [];

        $productIds = $order->orderItems->pluck('product_id')->unique();
        $warehouses = ProductWarehouse::with('warehouse')
            ->whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id');
//TODO:if needed need to avoid sql in loop
        foreach ($order->orderItems as $item) {
            $productId = $item->product_id;
            $needed = (int)$item->quantity;

            if (!isset($warehouses[$productId])) {
                throw new Exception("Product not found in any Warehouse (product_id=$productId)");
            }

            $productWarehouse = $warehouses[$productId];

            if ($productWarehouse->amount < $needed) {
                throw new Exception("Not enough stock for product=$productId");
            }

            $allocations[$productId] = [
                'warehouse_id' => $productWarehouse->warehouse_id,
                'quantity' => $needed,
            ];
        }

        return $allocations;
    }

    public function searchProduct(SearchProductsRequest $request): Collection
    {
        $needle = $request->validated('needle');

        return Product::where('name', 'LIKE', "%{$needle}%")->get();
    }
}
