<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Requests\Products\SearchProductsRequest;
use App\Http\Traits\CanSendJsonResponse;
use App\Http\Traits\CanSetFlashMessageTrait;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    use CanSetFlashMessageTrait, CanSendJsonResponse;

    public function __construct()
    {
        $this->middleware('admin')->except([
            'index',
            'show',
            'searchProduct'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ProductService $service): View
    {
        $product = $service->index();
        return view('client.products.index', ['products' => $product]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('client.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request, ProductService $service): RedirectResponse
    {
        $product = $service->store($request);
        $this->setMessage('🥳 Created Successfully');

        return redirect()->route('products.show', ['product' => $product]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
//        $product->quantity_left =
        return view('client.products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('client.products.create', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateProductRequest $request, Product $product, ProductService $service): RedirectResponse
    {
        $service->update($request, $product);
        $this->setMessage('🥳 Product updated successfully');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        $this->setMessage('Product deleted successfully');

        return redirect()->route('products.index');
    }
    public function searchProduct(SearchProductsRequest $request, ProductService $service): JsonResponse
    {
        return $this->sendResponse($service->searchProduct($request));
    }
}
