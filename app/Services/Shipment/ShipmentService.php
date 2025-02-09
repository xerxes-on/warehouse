<?php

declare(strict_types=1);

namespace App\Services\Shipment;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Http\Requests\Shipment\CreateShipmentRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductWarehouse;
use App\Models\Shipment;
use Exception;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\HttpFoundation\Response;

class ShipmentService
{
    public function createShipment(CreateShipmentRequest $request)
    {
        $valid = $request->validated();
        try {
            DB::beginTransaction();
            $orders = Order::whereIn('id', $valid['ids'])->where('status', OrderStatus::ORDERED)->get();
            if ($orders->isEmpty()) {
                throw new Exception('Invalid IDs or status');
            }
            $shipment = $this->createNewShipment($valid);
            $this->updateOrderStatuses($valid, $shipment);
            DB::commit();

            return $shipment->id;
        } catch (Exception $e) {
            DB::rollBack();
            abort(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
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
}
