<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderItemService
{
    public function addProduct(Request $request): int
    {
        $validated = $this->validateAddProduct($request);
        $product = Product::find($validated['id']);
        $user = User::find(auth()->user()->id);
        $order = $user->getCart();
        $existing = $order->orderItems()->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->update([
                'quantity' => $validated['quantity'],
                'total_price' => $validated['quantity'] * $product->price,
            ]);

            return $order->orderItems->unique('product_id')->count();
        }
        $this->createOrder($order, $product, $validated['quantity']);

        return $order->orderItems->unique('product_id')->count();
    }

    private function validateAddProduct(Request $request): array
    {
        return $request->validate([
            'id' => 'numeric|required|exists:products',
            'quantity' => 'numeric|required|min:1',
        ]);
    }

    private function createOrder(Order $order, Product $product, $quantity): void
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

    public function removeItems(Request $request): bool
    {
        $validated = $request->validate([
            'item_ids' => 'array|required',
        ]);
        $user = User::find(auth()->user()->id);
        $order = $user->getCart();
        $order->orderItems()->whereIn('product_id', $validated['item_ids'])->delete();

        return true;
    }

    public function editItems(Request $request): bool
    {
        //        TODO: need to optimize this
        $data = $request->validate([
            'products' => 'required|array',
        ]);
        $order = User::find(auth()->user()->id)->getCart();
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
