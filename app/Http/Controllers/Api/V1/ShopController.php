<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ShopResource;

class ShopController extends Controller
{
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
}
