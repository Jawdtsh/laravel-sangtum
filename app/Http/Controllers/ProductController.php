<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Traits\ResponseHandlerTrait;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ResponseHandlerTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = Product::all();
        return $this->successResponse($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        Product::create($request->validated());

        return $this->successResponse('Product created successfully',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): Product
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());
        return $this->successResponse('Product updated successfully');
    }


    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return $this->successResponse('Product deleted successfully');
    }
}
