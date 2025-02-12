<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Cache;

class OrderCalculationService
{
    public function calculateOrder(Order $order): array
    {
        if ($order->status !== OrderStatus::CART) {
            $cacheKey = 'order_calculation_' . $order->id;
            $cachedResult = Cache::get($cacheKey);
            if ($cachedResult !== null) {
                return $cachedResult;
            }
        }
        $discountPercentage = $this->isSaturday() ? config('fees.saturdayDiscount',0):0;
        $subtotal = $order->getSubtotal();
        $discount = $this->percentage($subtotal, $discountPercentage);
        $totalBeforeFees = $subtotal - $discount;
        $storeFee = $this->percentage($totalBeforeFees, config('fees.store',5));
        $dutiesFee = $this->percentage($totalBeforeFees, config('fees.duties',7.3));
        $tax = $this->percentage($totalBeforeFees, config('fees.tax',2.69));

        $result = [
            'total' => $totalBeforeFees + $storeFee + $dutiesFee + $tax,
            'storeFee' => number_format($storeFee, 2),
            'dutiesFee' => number_format($dutiesFee, 2),
            'tax' => number_format($tax, 2),
            'subtotal' => number_format($subtotal, 2),
            'totalBeforeFees' => number_format($totalBeforeFees, 2),
            'discount' => $discount,
            'hasDiscount' => $discount > 0,
        ];

        return $result;
    }

    private function isSaturday(): bool
    {
        $expiresAt = Carbon::now()->endOfDay();

        return Cache::remember('isSaturday', $expiresAt, function () {
            $today = new DateTime();
            $dayOfWeek = $today->format('N');
            $dayOfMonth = $today->format('j');

            return $dayOfWeek == 4 && $dayOfMonth >= 8 && $dayOfMonth <= 14;
        });
    }

    private function percentage(float $total, float $percent): float
    {
        return ($total * $percent) / 100;
    }
}
