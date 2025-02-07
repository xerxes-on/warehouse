<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Warehouse;
use App\Services\Orders\OrderCalculationService;
use App\Services\Orders\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('client.orders.index', [
            'orders' => Order::paginate(15),
            'canCreate' => false,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Order $order, OrderCalculationService $service): View
    {
        $details = $service->calculateOrder($order);

        return view('client.orders.show', ['order' => $order, 'details' => $details]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order, OrderService $service): RedirectResponse
    {
        try {
            $service->changeStatus($request, $order);
        } catch (Exception $e) {
            session()->flash('message', 'Error '.$e->getMessage());

            return redirect()->back();
        }
        session()->flash('message', 'Thanks, Order updated successfully 🤝');

        return redirect()->route('orders.show', $order->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {}

    public function showDelivered(): View
    {
        return view('client.orders.index', [
            'orders' => Order::where('status', OrderStatus::DELIVERED)->paginate(15),
            'canCreate' => false,
        ]);
    }

    public function showOrdered(): View
    {
        return view('client.orders.index', [
            'orders' => Order::where('status', OrderStatus::ORDERED)->paginate(15),
            'canCreate' => true,
            'warehouses' => Warehouse::all(),
            'branches' => Branch::all(),
        ]);
    }
}
