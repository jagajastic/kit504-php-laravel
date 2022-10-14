<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Cart\StoreRequest;

class CartController extends Controller
{
    /**
     * Get Cart.
     */
    public function show(Request $request, Shop $shop): JsonResponse
    {
        return $this->ok($request->user()->getCart($shop->id));
    }

    /**
     * Add Cart Item.
     */
    public function store(StoreRequest $request, Shop $shop): JsonResponse
    {
        $cart    = $request->user()->getCart($shop->id);
        $product = $shop->products()->whereId($request->product_id)->first();

        if ($product === \null) {
            return $this->error(\null, 'Product is not in shop.');
        }

        if (isset($cart[$product->id])) {
            return $this->error(\null, 'Item is already in cart.');
        }

        $cart[$product->id] = [
            'comment'    => $request->comment,
            'quantity'   => $request->quantity ?? 1,
        ];

        $request->user()->setCart($shop->id, $cart);

        return $this->ok(
            \null,
            JsonResponse::HTTP_ACCEPTED,
            'Item has been added to cart.',
        );
    }
}
