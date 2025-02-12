<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Http\Requests\Orders\AddProductsToOrderRequest;
use App\Http\Requests\Orders\RemoveItemsRequest;
use App\Http\Requests\Products\EditProductRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class OrderItemService
{
    public function addProduct(AddProductsToOrderRequest $request): int
    {
        $validated = $request->validated();
        $product = Product::find($validated['id']);
        $user = auth()->user();
        $order = $user->cart;
        $existing = $order->orderItems()->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->update([
                'quantity' => $validated['quantity'],
                'total_price' => $validated['quantity'] * $product->price,
            ]);

            return $order->orderItems->unique('product_id')->count();
        }
        $this->createOrder($order, $product, intval($validated['quantity']));

        return $order->orderItems->unique('product_id')->count();
    }


    private function createOrder(Order $order, Product $product, int $quantity): void
    {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'total_price' => $quantity * $product->price,
        ]);
    }

    public function removeItems(RemoveItemsRequest $request): bool
    {
        $validated = $request->validated();
        $user = auth()->user();
        $order = $user->cart;
        $order->orderItems()->whereIn('product_id', $validated['item_ids'])->delete();

        return true;
    }

    public function editItems(EditProductRequest $request): bool
    {
        $data = $request->validated();
        $order = auth()->user()->cart;
        foreach ($data['products'] as $item) {
            $orderItem = $order->orderItems()->where('product_id', $item['id'])->first();
            if ($orderItem && is_numeric($item['amount'])) {
                $orderItem->update([
                    'quantity' => $item['amount'],
                    'total_price' => $item['amount'] * $orderItem->price,
                ]);
            }
        }

        return true;
    }
}
