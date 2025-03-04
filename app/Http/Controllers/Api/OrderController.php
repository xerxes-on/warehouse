<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\ChangeOrderStatusRequest;
use App\Http\Requests\Orders\GetOrderRequest;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Branch;
use App\Models\Order;
use App\Services\Orders\OrderCalculationService;
use App\Services\Orders\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    use CanSendJsonResponse;

    public function index(GetOrderRequest $request): JsonResponse
    {
        $query = Order::with('shipments');

        if ($request->filled('status')) {
            $query->where('status', OrderStatus::from($request->status));
        } else {
            $query->where('status', '!=', OrderStatus::CART);
        }
        return $this->sendResponse(['orders' => $query->get()]);
    }


    public function show(Order $order, OrderCalculationService $service): JsonResponse
    {
        $details = $service->calculateOrder($order);
        $branches = Cache::remember('branches', now()->addDay(), function () {
            return Branch::all();
        });
        return $this->sendResponse([
            'order' => $order->load('orderItems', 'shipments'),
            'details' => $details,
            'branches' => $branches,
        ]);
    }

    public function update(ChangeOrderStatusRequest $request, Order $order, OrderService $service): JsonResponse
    {
        try {
            $service->checkout($request, $order);
            return $this->sendResponse(null);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function cart(OrderCalculationService $calculator): JsonResponse
    {
        $calculations = $calculator->calculateOrder(auth()->user()->cart);
        return $this->sendResponse(['cart' => auth()->user()->cart->load('orderItems'), 'calculations' => $calculations]);
    }

    public function getBranches(): JsonResponse
    {
        return $this->sendResponse(['branches' => Branch::all()]);
    }
    public function calculations(Order $order, OrderCalculationService $service): JsonResponse
    {
        return $this->sendResponse(['calculations' => $service->calculateOrder($order)]);
    }


}
