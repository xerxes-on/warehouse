<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;

class OrderService
{
    /**
     * @throws Exception
     */
    public function changeStatus(Request $request, Order $order): void
    {
        $valid = $request->validate([
            'status' => 'required|numeric|min:0|max:3',
        ]);
        if ((int) $valid['status'] < (int) $order->status->value) {
            throw new Exception('Cannot downgrade an order');
        }
        if ($order->status !== OrderStatus::CART) {
            $order->update([
                'status' => OrderStatus::from($valid['status']),
            ]);

            return;
        }
        $availability = ProductAvailabilityService::productsAvailability($order);
        $allAvailable = true;
        foreach ($order->orderItems as $item) {
            if (empty($availability[$item->product_id]) || $availability[$item->product_id]->count() === 0) {
                $allAvailable = false;
                break;
            }
        }
        $service = new OrderCalculationService();
        if ($allAvailable) {
            $order->update([
                'status' => OrderStatus::from($valid['status']),
                'total_price' => $service->calculateOrder($order)['total'],
            ]);

            return;
        }
        throw new Exception('Some Products are out of stock');
    }
}
