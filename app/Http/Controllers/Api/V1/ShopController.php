<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ShopResource;
use App\Http\Requests\Api\V1\Shop\StoreRequest;

class ShopController extends Controller
{
    /**
     * Create [ShopController] instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Get all shops.
     */
    public function index(): JsonResponse
    {
        return $this->ok(ShopResource::collection(Shop::all()));
    }

    /**
     * Get a shop.
     */
    public function show(Shop $shop): JsonResponse
    {
        return $this->ok(new ShopResource($shop));
    }

    /**
     * Create shop.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $shop = Shop::create($request->validated());

        $shop->refresh();

        return $this->ok(
            new ShopResource($shop),
            JsonResponse::HTTP_CREATED,
            'Shop created.',
            [
                'Location' => \action([$this::class, 'show'], $shop),
            ]
        );
    }
}
