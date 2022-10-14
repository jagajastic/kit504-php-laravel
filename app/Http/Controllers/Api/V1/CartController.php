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
        $id      = $request->product_id;
        $cart    = $request->user()->getCart($shop->id);
        $product = $shop->products()->whereId($id)->first();

        if ($product === \null) {
            return $this->error(\null, 'Product is not in shop.');
        }

        if (isset($cart[$id])) {
            return $this->error(\null, 'Item is already in cart.');
        }

        $cart[$id] = [
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

    /**
     * Delete Cart Item.
     */
    public function destroy(Request $request, Shop $shop, string $productId)
    {
        $cart = $request->user()->getCart($shop->id);

        if (!isset($cart[$productId])) {
            return $this->error(
                \null,
                'Item is not in cart.',
                JsonResponse::HTTP_NOT_FOUND,
            );
        }

        unset($cart[$productId]);

        $request->user()->setCart($shop->id, $cart);

        return $this->ok(
            \null,
            JsonResponse::HTTP_NO_CONTENT,
        );
    }
}
