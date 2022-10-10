<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use App\Enums\UserType;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Middleware\UserTypeMiddleware;
use App\Http\Resources\Api\V1\ShopResource;
use App\Http\Requests\Api\V1\Shop\StoreRequest;
use App\Http\Requests\Api\V1\Shop\UpdateRequest;

class ShopController extends Controller
{
    /**
     * Create [ShopController] instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware(UserTypeMiddleware::make([UserType::DIRECTOR, UserType::SHOP_MANAGER]))
            ->only(['update']);
    }

    /**
     * Get all shops.
     */
    public function index(): JsonResponse
    {
        return $this->ok(ShopResource::collection(Shop::paginated()));
    }

    /**
     * Get a shop.
     */
    public function show(Shop $shop): JsonResponse
    {
        return $this->ok(new ShopResource(
            $shop->load([
                'products',
            ])
        ));
    }

    /**
     * Create a shop.
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
     * Update a shop.
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
     * Delete a shop.
     */
    public function destroy(Shop $shop): JsonResponse
    {
        $shop->delete();

        return $this->ok(
            \null,
            JsonResponse::HTTP_NO_CONTENT,
        );
    }
}
