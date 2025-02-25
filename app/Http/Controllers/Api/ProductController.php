<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Requests\Products\SearchProductsRequest;
use App\Http\Traits\CanSendJsonResponse;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Client\Request;
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
    public function create(CreateProductRequest $request, ProductService $service): JsonResponse
    {
        $service->store($request);
        return $this->sendResponse(null);
    }
    public function update(CreateProductRequest $request,Product $product, ProductService $service): JsonResponse
    {
        $service->update($request, $product);
        return $this->sendResponse(null);
    }
    public function destroy(Product $product): JsonResponse
    {
        $product->update(['deleted_at' =>now()]);
        return $this->sendResponse(null);
    }
}
