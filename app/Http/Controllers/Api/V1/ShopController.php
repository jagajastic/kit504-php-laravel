<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    /**
     * Get all shops.
     */
    public function index(): JsonResponse
    {
        return $this->ok(Shop::all());
    }

    /**
     * Get a shop.
     */
    public function show(Shop $shop): JsonResponse
    {
        return $this->ok($shop);
    }
}
