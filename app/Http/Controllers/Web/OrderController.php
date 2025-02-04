<?php

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('client.orders.index', ['orders' => Order::paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View
    {
        $service = new OrderService();
        $details = $service->calculateOrder($order);
        return view('client.orders.show', ['order' => $order, 'details' => $details]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showDelivered(): View
    {
        return view('client.orders.index', ['orders' => Order::where('status', OrderStatus::DELIVERED)->paginate(15)]);
    }

    public function showOrdered(): View
    {
        return view('client.orders.index', ['orders' => Order::where('status', OrderStatus::ORDERED)->paginate(15)]);
    }
}
