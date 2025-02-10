<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use CanSendJsonResponse;

    public function index(ProductService $service): JsonResponse
    {
        $product = $service->index();
        return $this->sendResponse($product);
    }

    public function show(Product $product): JsonResponse
    {
        return $this->sendResponse($product);
    }
    public function searchProduct(Request $request): JsonResponse
    {
        $data = $request->validate([
            'needle' => 'required|min:2',
        ]);

        $results = Product::where('name', 'LIKE', "%{$data['needle']}%")->get();

        return $this->sendResponse($results);
    }
}
