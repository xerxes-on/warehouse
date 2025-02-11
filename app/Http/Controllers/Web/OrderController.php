<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\ChangeOrderStatusRequest;
use App\Http\Traits\CanSetFlashMessageTrait;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Warehouse;
use App\Services\Orders\OrderCalculationService;
use App\Services\Orders\OrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class OrderController extends Controller
{
    use CanSetFlashMessageTrait;

    public function index(): View
    {
        return view('client.orders.index', [
            'orders' => Order::paginate(15),
            'canCreate' => false,
        ]);
    }

    public function show(Order $order, OrderCalculationService $service): View
    {
        $details = $service->calculateOrder($order);
        $branches = Cache::remember('branches', now()->addDay(), function () {
            return Branch::all();
        });
        return view('client.orders.show', [
            'order' => $order->load('shipments'),
            'details' => $details,
            'branches' => $branches
        ]);
    }

    public function update(ChangeOrderStatusRequest $request, Order $order, OrderService $service): RedirectResponse
    {
        try {
            $service->checkout($request, $order);
        } catch (Exception $e) {
            $this->setMessage('Error ' . $e->getMessage());
            return redirect()->back();
        }
        $this->setMessage('Thanks, Order updated successfully 🤝');

        return redirect()->route('orders.show', $order->id);
    }

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
