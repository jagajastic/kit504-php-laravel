<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use App\Enums\UserType;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Middleware\UserTypeMiddleware;
use App\Http\Resources\Api\V1\ShopResource;
use App\Http\Resources\Api\V1\ProductResource;
use App\Http\Requests\Api\V1\Shop\StoreRequest;
use App\Http\Requests\Api\V1\Shop\UpdateRequest;
use App\Http\Requests\Api\V1\Shop\ProductUpdateRequest;

class ShopController extends Controller
{
    /**
     * Create [ShopController] Instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show', 'productsIndex']);
        $this->middleware(UserTypeMiddleware::make([UserType::DIRECTOR, UserType::SHOP_MANAGER]))
            ->only(['update', 'productsShow', 'productsUpdate']);
    }

    /**
     * Get Shops.
     */
    public function index(): JsonResponse
    {
        return $this->ok(ShopResource::collection(Shop::paginated()));
    }

    /**
     * Get Shop.
     */
    public function show(Shop $shop): JsonResponse
    {
        return $this->ok(new ShopResource($shop));
    }

    /**
     * Create Shop.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $shop = Shop::create($request->validated())->refresh();

        return $this->ok(
            new ShopResource($shop),
            JsonResponse::HTTP_CREATED,
            'Shop created.',
            [
                'Location' => \action([$this::class, 'show'], $shop),
            ]
        );
    }

    /**
     * Update Shop.
     */
    public function update(UpdateRequest $request, Shop $shop): JsonResponse
    {
        $shop->update($request->validated());

        $shop->refresh();

        return $this->ok(
            new ShopResource($shop),
            JsonResponse::HTTP_ACCEPTED,
            'Shop updated.'
        );
    }

    /**
     * Delete Shop.
     */
    public function destroy(Shop $shop): JsonResponse
    {
        $shop->delete();

        return $this->ok(
            \null,
            JsonResponse::HTTP_NO_CONTENT,
        );
    }

    /**
     * Get Shop Products.
     */
    public function productsIndex(Shop $shop): JsonResponse
    {
        return $this->ok(ProductResource::collection($shop->products()->paginated()));
    }

    /**
     * Get Shop Product.
     */
    public function productsShow(Shop $shop, string $product): JsonResponse
    {
        return $this->ok(new ProductResource(
            $shop->products()->whereId($product)->firstOrFail()
        ));
    }

    /**
     * Manage Shop Products.
     */
    public function productsUpdate(Shop $shop, ProductUpdateRequest $request): JsonResponse
    {
        $shop->products()->sync($request->products);

        return $this->ok(
            \null,
            JsonResponse::HTTP_CREATED,
            'Products has been synced to shop.'
        );
    }
}
