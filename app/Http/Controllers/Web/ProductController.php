<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    private ProductService $service;

    public function __construct()
    {
        $this->service = new ProductService();
        $this->middleware('admin')->only([
            'store',
            'destroy',
            'create',
            'update',
            'edit'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('client.products.index', ['products' => $this->service->index()]);
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
    public function store(Request $request): RedirectResponse
    {
        $product = $this->service->store($request);
        return redirect()->route('products.show', ['product' => $product]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
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
    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->service->update($request, $product);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
//        right now its soft deletes TODO: think about other solutions
        $product->delete();
        session()->flash('message', 'Product deleted successfully');
        return redirect()->route('products.index');
    }
}
