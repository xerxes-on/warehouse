<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\ProductWarehouse;

class ProductAvailabilityService
{
    public static function productsAvailability(Order $order): array
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
