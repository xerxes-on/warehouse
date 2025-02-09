<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\AddProductsToOrderRequest;
use App\Http\Requests\Orders\RemoveItemsRequest;
use App\Http\Requests\Products\EditProductRequest;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Order;
use App\Services\Orders\OrderCalculationService;
use App\Services\Orders\OrderItemService;
use Illuminate\Http\JsonResponse;

class OrderItemController extends Controller
{
    use CanSendJsonResponse;

    public function add(AddProductsToOrderRequest $request, OrderItemService $orderItemService): JsonResponse
    {
        $response = $orderItemService->addProduct($request);

        return $response ?
            $this->sendResponse($response) :
            $this->sendError();
    }

    public function removeItems(RemoveItemsRequest $request, OrderItemService $orderItemService): JsonResponse
    {
        $response = $orderItemService->removeItems($request);

        return $response ?
            $this->sendResponse(null) :
            $this->sendError();
    }

    public function refreshOrderDetails(Order $order, OrderCalculationService $calculationService): JsonResponse
    {
        $details = $calculationService->calculateOrder($order);

        return $details ?
            $this->sendResponse($details) :
            $this->sendError();
    }

    public function updateItems(EditProductRequest $request, OrderItemService $orderItemService): JsonResponse
    {
        return $orderItemService->editItems($request) ?
            $this->sendResponse(null) :
            $this->sendError();
    }
}
