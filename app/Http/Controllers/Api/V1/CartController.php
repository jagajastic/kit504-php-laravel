<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    /**
     * Get Cart.
     */
    public function show(Request $request, Shop $shop): JsonResponse
    {
        return $this->ok($request->user()->getCart($shop->id));
    }
}
