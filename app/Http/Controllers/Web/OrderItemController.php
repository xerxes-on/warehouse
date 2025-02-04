<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    use CanSendJsonResponse;

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'numeric|required|exists:products',
            'quantity' => 'numeric|required'
        ]);
        $service = new OrderService();
        $response = $service->addProduct(Product::find($validated['id']), $validated['quantity'],
            User::find(auth()->user()->id));
        return $response ?
            $this->sendResponse($response) :
            $this->sendError();
    }
}
