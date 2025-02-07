<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Traits\CanSetFlashMessageTrait;
use App\Models\Shipment;
use App\Services\Shipment\ShipmentService;
use App\Services\Shipment\UpdateShipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    use CanSetFlashMessageTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('client.shipments.index', [
            'shipments' => Shipment::paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ShipmentService $service): RedirectResponse
    {
        return redirect()->route('shipment.show', $service->createShipment($request));
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment): View
    {
        return view('client.shipments.show', ['shipment' => $shipment]);
    }

    public function update(Request $request, Shipment $shipment, UpdateShipmentService $service): RedirectResponse
    {
        $service->updateShipment($request, $shipment) ?
            $this->sendMessage('Status Updated') : $this->sendMessage('Error occurred');

        return redirect()->back();
    }

    public function showDelivering(): View
    {
        return view('client.shipments.index', [
            'shipments' => Shipment::where('status', ShipmentStatus::DELIVERING)->paginate(20),
        ]);
    }

    public function showDelivered(): View
    {
        return view('client.shipments.index', [
            'shipments' => Shipment::where('status', ShipmentStatus::DELIVERED)->paginate(20),
        ]);
    }

    public function showReturned(): View
    {
        return view('client.shipments.index', [
            'shipments' => Shipment::where('status', ShipmentStatus::RETURNED)->paginate(20),
        ]);
    }
}
