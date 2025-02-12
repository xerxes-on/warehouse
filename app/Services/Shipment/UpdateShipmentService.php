<?php

declare(strict_types=1);

namespace App\Services\Shipment;

use App\Enums\OrderStatus;
use App\Enums\ShipmentStatus;
use App\Http\Requests\Shipment\UpdateShipmentRequest;
use App\Models\Shipment;

class UpdateShipmentService
{
    public function updateShipment(UpdateShipmentRequest $request, Shipment $shipment): bool
    {
        $status = $request->validated('status');
        return match ((int)$status) {
            ShipmentStatus::DELIVERED->value => $this->delivered($shipment),
            ShipmentStatus::RETURNED->value => $this->returned($shipment),
            default => false,
        };
    }

    private function delivered(Shipment $shipment): bool
    {
        $shipment->update(['status' => ShipmentStatus::DELIVERED, 'date_shipped' => now()]);
        $order = $shipment->order;
        $undeliveredShipments = $order->shipments()->where('status', '!=', ShipmentStatus::DELIVERED)->count();
        if ($undeliveredShipments === 0) {
            $order->update(['status' => OrderStatus::DELIVERED]);
        }
        return true;
    }

    private function returned(Shipment $shipment): bool
    {
        $shipment->update(['status' => ShipmentStatus::RETURNED]);
        return true;
    }
}
