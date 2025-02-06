<?php

namespace App\Services;

use App\Enums\Fees;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OrderService
{
    public function addProduct(Request $request): int
    {
        $validated = $request->validate([
            'id' => 'numeric|required|exists:products',
            'quantity' => 'numeric|required|min:1'
        ]);
        $product = Product::find($validated['id']);
        $quantity = $validated['quantity'];
        $user = User::find(auth()->user()->id);
        $order = $user->getCart();
        $existing = $order->orderItems()->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->update([
                'quantity' => $quantity,
                'total_price' => $quantity * $product->price
            ]);
            return $order->orderItems->unique('product_id')->count();
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

    /**
     * @throws \Exception
     */
    public function changeStatus(Request $request, Order $order): void
    {
        $valid = $request->validate([
            'status' => 'required|numeric|min:0|max:3',
        ]);
        if ((int) $valid['status'] < (int) $order->status->value) {
            throw new \Exception('Cannot downgrade an order');
        }
        if ($order->status != OrderStatus::CART) {
            $order->update([
                'status' => OrderStatus::from($valid['status'])
            ]);
            return;
        }
        $availability = $this->productsAvailability($order);
        $allAvailable = true;
        foreach ($order->orderItems as $item) {
            if (empty($availability[$item->product_id]) || $availability[$item->product_id]->count() === 0) {
                $allAvailable = false;
                break;
            }
        }
        if ($allAvailable) {
            $order->update([
                'status' => OrderStatus::from($valid['status'])
            ]);
            return;
        }
        throw new \Exception('Some Products are out of stock');
    }

    public function calculateOrder(Order $order): array
    {
        if ($order->status != OrderStatus::CART) {
            $cacheKey = 'order_calculation_'.$order->id;
            $cachedResult = Cache::get($cacheKey);
            if ($cachedResult !== null) {
                return $cachedResult;
            }
        }
        $discountPercentage = $this->isSaturday() ? Fees::$saturdayDiscount : 0;
        $subtotal = $order->getSubtotal();
        $discount = $this->percentage($subtotal, $discountPercentage);
        $totalBeforeFees = $subtotal - $discount;
        $storeFee = $this->percentage($totalBeforeFees, Fees::$store);
        $dutiesFee = $this->percentage($totalBeforeFees, Fees::$duties);
        $tax = $this->percentage($totalBeforeFees, Fees::$tax);

        $result = [
            'total' => number_format($totalBeforeFees + $storeFee + $dutiesFee + $tax, 2),
            'storeFee' => number_format($storeFee, 2),
            'dutiesFee' => number_format($dutiesFee, 2),
            'tax' => number_format($tax, 2),
            'subtotal' => number_format($subtotal, 2),
            'totalBeforeFees' => number_format($totalBeforeFees, 2),
            'discount' => $discount,
            'hasDiscount' => $discount > 0
        ];
        if ($order->status != OrderStatus::CART) {
            Cache::forever($cacheKey, $result);
        }

        return $result;
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

    public function productsAvailability(Order $order): array
    {
        $availability = [];
        foreach ($order->orderItems as $item) {
            $warehouses = ProductWarehouse::with('warehouse')
                ->where('product_id', $item->product_id)
                ->where('amount', '>=', $item->quantity)
                ->get();

            $availability[$item->product_id] = $warehouses;
        }

        return $availability;
    }
}
