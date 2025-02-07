<?php

declare(strict_types=1);

namespace App\Services\Shipment;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductWarehouse;
use App\Models\Shipment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\HttpFoundation\Response;

class ShipmentService
{
    public function createShipment(Request $request)
    {
        $valid = $this->validateCreation($request);
        try {
            DB::beginTransaction();
            $orders = Order::whereIn('id', $valid['ids'])->where('status', OrderStatus::ORDERED)->get();
            if ($orders->isEmpty()) {
                throw new Exception('Invalid IDs or status');
            }
            $shipment = $this->createNewShipment($valid);
            $this->updateOrderStatuses($valid, $shipment);
            $this->removeProductsFromWarehouse($orders, $shipment);
            DB::commit();

            return $shipment->id;
        } catch (Exception $e) {
            DB::rollBack();
            abort(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function validateCreation(Request $request): array
    {
        return $request->validate([
            'ids' => 'array|required',
            'warehouse_id' => 'required|exists:warehouses,id',
            'branch_id' => 'required|exists:branches,id',
        ]);
    }

    private function createNewShipment(array $valid): Shipment
    {
        return Shipment::create([
            'warehouse_id' => $valid['warehouse_id'],
            'branch_id' => $valid['branch_id'],
            'status' => ShipmentStatus::DELIVERING,
        ]);
    }

    private function updateOrderStatuses(array $valid, Shipment $shipment): void
    {
        Order::whereIn('id', $valid['ids'])
            ->where('status', OrderStatus::ORDERED)
            ->update(['shipment_id' => $shipment->id]);
    }

    /**
     * @throws Exception
     */
    private function removeProductsFromWarehouse(Collection $orders, Shipment $shipment): void
    {
        foreach ($orders as $order) {
            $orderItems = OrderItem::where('order_id', $order->id)->get();
            foreach ($orderItems as $orderItem) {
                $warehouseProduct = ProductWarehouse::where([
                    'warehouse_id' => $shipment->warehouse_id,
                    'product_id' => $orderItem->product_id,
                ])->first();
                if (! $warehouseProduct || $warehouseProduct->quantity < $orderItem->quantity) {
                    throw new Exception('Insufficient stock for product.');
                }
                $warehouseProduct->decrement('amount', $orderItem->quantity);
                if ($warehouseProduct->amount === 0) {
                    $warehouseProduct->delete();
                }
            }
        }
    }
}
