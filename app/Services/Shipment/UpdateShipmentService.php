<?php

namespace App\Services\Shipment;

use App\Enums\ShipmentStatus;
use App\Models\Shipment;
use Illuminate\Http\Request;

class UpdateShipmentService
{
    public function updateShipment(Request $request, Shipment $shipment): bool
    {
        $status = $request->input('status');
        if (!$status) {
            return false;
        }
        return match ((int) $status) {
            ShipmentStatus::DELIVERED->value => $this->delivered($shipment),
            ShipmentStatus::RETURNED->value => $this->returned($shipment),
            default => false,
        };
    }

    private function delivered(Shipment $shipment): bool
    {
        $shipment->update([
            'status' => ShipmentStatus::DELIVERED->value,
            'date_shipped' => now()
        ]);
        return true;
    }

    private function returned(Shipment $shipment): bool
    {
        $shipment->update([
            'status' => ShipmentStatus::RETURNED->value,
        ]);
        return true;
    }
}
