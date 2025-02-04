<?php

namespace App\Services;

use App\Enums\Fees;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Cache;

class OrderService
{
    public function addProduct(Product $product, int $quantity, User $user): int
    {
        if (!Order::where('status', OrderStatus::CART)->exists()) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => OrderStatus::CART,
                'total_price' => 0,
            ]);
        } else {
            $order = $user->getCart();
        }

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'total_price' => $quantity * $product->price
        ]);
        return $order->orderItems->unique('product_id')->count();
    }

    public function calculateOrder(Order $order): array
    {
        $discountPercentage = $this->isSaturday() ? Fees::$saturdayDiscount : 0;
        $subtotal = $order->getSubtotal();
        $discount = $this->percentage($subtotal, $discountPercentage);
        $totalBeforeFees = $subtotal - $discount;
        $storeFee = $this->percentage($totalBeforeFees, Fees::$store);
        $dutiesFee = $this->percentage($totalBeforeFees, Fees::$duties);
        $tax = $this->percentage($totalBeforeFees, Fees::$tax);
        return [
            'total' => $totalBeforeFees + $storeFee + $dutiesFee + $tax,
            'storeFee' => $storeFee,
            'dutiesFee' => $dutiesFee,
            'tax' => $tax,
            'subtotal' => $subtotal,
            'totalBeforeFees' => $totalBeforeFees,
            'discount' => $discount
        ];
    }

    private function percentage($total, $percent): float
    {
        return ($total * $percent) / 100;
    }

    private function isSaturday(): bool
    {
        $expiresAt = Carbon::now()->endOfDay();
        return Cache::remember('isSaturday', $expiresAt, function () {
            $today = new DateTime();
            $dayOfWeek = $today->format('N');
            $dayOfMonth = $today->format('j');

            return ($dayOfWeek == 6 && $dayOfMonth >= 8 && $dayOfMonth <= 14);
        });
    }
}
