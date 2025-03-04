<?php

namespace App\Http\Controllers\Api;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shipment\GetFilteredShipment;
use App\Http\Requests\Shipment\UpdateShipmentRequest;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Shipment;
use App\Services\Shipment\UpdateShipmentService;
use Illuminate\Http\JsonResponse;

class ShipmentController extends Controller
{
    use CanSendJsonResponse;

    public function index(GetFilteredShipment $request): JsonResponse
    {
        if (isset($request->status)) {
            return $this->sendResponse(['shipments' => Shipment::all()
                ->where('status', ShipmentStatus::from($request->status))
                ->load('order')]);
        }
        return $this->sendResponse(['shipments' => Shipment::all()->load('order')]);
    }

    public function show(Shipment $shipment): JsonResponse
    {
        $relationships = ['order', 'branch','warehouse', 'shipmentItems.orderItem.product'];

        $shipment->load($relationships);
        return $this->sendResponse(['shipment' => $shipment]);
    }

    public function update(UpdateShipmentRequest $request, Shipment $shipment, UpdateShipmentService $service): JsonResponse
    {
        return $service->updateShipment($request, $shipment) ?
            $this->sendResponse('Status Updated') : $this->sendError('Error occurred');
    }
}
