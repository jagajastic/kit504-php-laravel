<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Cart\StoreRequest;
use App\Http\Requests\Api\V1\Cart\UpdateRequest;
use App\Http\Resources\V1\CartItemResource;

class CartController extends Controller
{
    /**
     * Get Cart.
     */
    public function show(Request $request, Shop $shop): JsonResponse
    {
        $items = [];
        $cart  = $request->user()->getCart($shop->id);

        foreach ($cart as $cartItem) {
            $items[] = new CartItemResource($cartItem);
        }

        return $this->ok($items);
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
            'id'         => $id,
            'comment'    => $request->comment,
            'quantity'   => $request->quantity ?? 1,
        ];

        $request->user()->setCart($shop->id, $cart);

        return $this->ok(
            new CartItemResource($cart[$id]),
            JsonResponse::HTTP_CREATED,
            'Item has been added to cart.',
        );
    }

    /**
     * Delete Cart Item.
     */
    public function destroy(
        Request $request,
        Shop $shop,
        string $productId
    ): JsonResponse {
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

    /**
     * Update Cart Item.
     */
    public function update(
        UpdateRequest $request,
        Shop $shop,
        string $productId
    ): JsonResponse {
        $cart = $request->user()->getCart($shop->id);

        if (!isset($cart[$productId])) {
            return $this->error(
                \null,
                'Item is not in cart.',
                JsonResponse::HTTP_NOT_FOUND,
            );
        }

        $updatedItemsCount = $request->safe()
            ->collect()
            ->each(function ($value, $key) use (&$cart, $productId) {
                $cart[$productId][$key] = $value;
            })
            ->count();

        if ($updatedItemsCount > 0) {
            $request->user()->setCart($shop->id, $cart);
        }

        return $this->ok(
            new CartItemResource($cart[$productId]),
            JsonResponse::HTTP_ACCEPTED,
            'Item has been updated.',
        );
    }

    /**
     * Clear Cart.
     */
    public function clear(Request $request, Shop $shop): JsonResponse
    {
        $request->user()->setCart($shop->id, []);

        return $this->ok(
            \null,
            JsonResponse::HTTP_NO_CONTENT,
        );
    }
}
