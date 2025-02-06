<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Order;
use App\Services\Orders\OrderCalculationService;
use App\Services\Orders\OrderItemService;
use App\Services\Orders\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    use CanSendJsonResponse;

    private OrderService $service;
    private OrderItemService $orderItemService;
    private OrderCalculationService $calculationService;

    public function __construct(
        OrderService $orderService,
        OrderItemService $orderItemService,
        OrderCalculationService $calculationService
    ) {
        $this->service = $orderService;
        $this->orderItemService = $orderItemService;
        $this->calculationService = $calculationService;
    }

    public function add(Request $request): JsonResponse
    {
        $response = $this->orderItemService->addProduct($request);
        return $response ?
            $this->sendResponse($response) :
            $this->sendError();
    }

    public function removeItems(Request $request): JsonResponse
    {
        $response = $this->orderItemService->removeItems($request);
        return $response ?
            $this->sendResponse(null) :
            $this->sendError();
    }

    public function refreshOrderDetails(Order $order): JsonResponse
    {
        $details = $this->calculationService->calculateOrder($order);
        return $details ?
            $this->sendResponse($details) :
            $this->sendError();
    }

    public function updateItems(Request $request): JsonResponse
    {
        return $this->orderItemService->editItems($request) ?
            $this->sendResponse(null) :
            $this->sendError();
    }
}
