<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\ChangeOrderStatusRequest;
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

    public function index(): JsonResponse
    {
        return $this->sendResponse(Order::all());
    }


    public function show(Order $order, OrderCalculationService $service): JsonResponse
    {
        $details = $service->calculateOrder($order);
        $branches = Cache::remember('branches', now()->addDay(), function () {
            return Branch::all();
        });
        return $this->sendResponse([$details, $branches]);
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

}
