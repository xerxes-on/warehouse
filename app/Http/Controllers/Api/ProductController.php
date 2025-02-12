<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\SearchProductsRequest;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use CanSendJsonResponse;

    public function index(ProductService $service): JsonResponse
    {
        $product = $service->index();
        return $this->sendResponse(['products' => $product]);
    }

    public function show(Product $product): JsonResponse
    {
        return $this->sendResponse(['product' => $product]);
    }

    public function searchProduct(SearchProductsRequest $request, ProductService $service): JsonResponse
    {
        return $this->sendResponse(['products' => $service->searchProduct($request)]);
    }
}
