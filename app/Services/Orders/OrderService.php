<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Http\Requests\Orders\ChangeOrderStatusRequest;
use App\Models\Order;
use App\Models\ProductWarehouse;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Services\ProductService;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @throws Exception
     */
    public function checkout(ChangeOrderStatusRequest $request, Order $order): void
    {
        $newStatusValue = (int)$request->validated('status');
        $branchId = (int)$request->validated('branch_id');

        if ($order->status !== OrderStatus::CART) {
            throw new Exception('Cannot change order that is not in cart');
        }

        $allocations = ProductService::allocateProducts($order);

        DB::beginTransaction();
        try {
            $this->removeProductsFromWarehouses($allocations);

            $this->createShipments($order, $allocations, $branchId);

            $calcService = new OrderCalculationService();
            $result =  $calcService->calculateOrder($order);
            $order->update([
                'status' => OrderStatus::from($newStatusValue),
                'total_price' =>$result['total'],
            ]);

            Cache::forever('order_calculation_' . $order->id, $result);

            DB::commit();
            Cache::forget('products');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function removeProductsFromWarehouses(array $allocations): void
    {
        foreach ($allocations as $productId => $data) {
            ProductWarehouse::where('product_id', $productId)
                ->where('warehouse_id', $data['warehouse_id'])
                ->decrement('amount', $data['quantity']);
        }
    }

    /**
     * @throws Exception
     */
    private function createShipments(Order $order, array $allocations, int $branchId): void
    {
        $byWarehouse = [];
        foreach ($order->orderItems as $orderItem) {
            $productId = $orderItem->product_id;
            if (!isset($allocations[$productId])) {
                continue;
            }
            $warehouseId = $allocations[$productId]['warehouse_id'];
            $byWarehouse[$warehouseId][] = $orderItem;
        }

        foreach ($byWarehouse as $warehouseId => $orderItems) {
            $shipment = Shipment::create([
                'order_id' => $order->id,
                'warehouse_id' => $warehouseId,
                'branch_id' => $branchId,
                'status' => ShipmentStatus::DELIVERING,
            ]);

            foreach ($orderItems as $orderItem) {
                ShipmentItem::create([
                    'shipment_id' => $shipment->id,
                    'order_item_id' => $orderItem->id,
                ]);
            }
        }
    }
}
