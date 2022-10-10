<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\StoreRequest;
use App\Http\Requests\Api\V1\Product\UpdateRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Get all products.
     */
    public function index(): JsonResponse
    {
        return $this->ok(ProductResource::collection(Product::paginated()));
    }

    /**
     * Get a product.
     */
    public function show(Product $product): JsonResponse
    {
        return $this->ok(new ProductResource(
            $product
        ));
    }

    /**
     * Create a product.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $product = Product::create($request->validated())->refresh();

        return $this->ok(
            new ProductResource($product),
            JsonResponse::HTTP_CREATED,
            'Product created.',
            [
                'Location' => \action([$this::class, 'show'], $product),
            ]
        );
    }

    /**
     * Update a product.
     */
    public function update(UpdateRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        $product->refresh();

        return $this->ok(
            new ProductResource($product),
            JsonResponse::HTTP_ACCEPTED,
            'Product updated.'
        );
    }

    /**
     * Delete a product.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->ok(
            \null,
            JsonResponse::HTTP_NO_CONTENT,
        );
    }
}
