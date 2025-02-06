<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    use CanSendJsonResponse;

    private OrderService $service;

    public function __construct()
    {
        $this->service = new OrderService();
    }

    public function add(Request $request): JsonResponse
    {
        $response = $this->service->addProduct($request);
        return $response ?
            $this->sendResponse($response) :
            $this->sendError();
    }

    public function removeItems(Request $request): JsonResponse
    {
        $response = $this->service->removeItems($request);
        return $response ?
            $this->sendResponse(null) :
            $this->sendError();
    }

    public function refreshOrderDetails(Order $order): JsonResponse
    {
        $details = $this->service->calculateOrder($order);
        return $details ?
            $this->sendResponse($details) :
            $this->sendError();
    }

    public function updateItems(Request $request): JsonResponse
    {
        return $this->service->editItems($request) ?
            $this->sendResponse(null) :
            $this->sendError();
    }
}
