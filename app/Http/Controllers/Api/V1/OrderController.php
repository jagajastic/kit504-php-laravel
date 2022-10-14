<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Order;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\StoreRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Shop;

class OrderController extends Controller
{
    /**
     * Get Orders.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        switch ($user->type) {
            case UserType::DIRECTOR:
                $orders = Order::paginated();
                break;

            case UserType::SHOP_MANAGER:
            case UserType::SHOP_STAFF:
                $orders = Order::whereShopId($user->shop_id)->paginated();
                break;

            case UserType::UTAS_EMPLOYEE:
            case UserType::UTAS_STUDENT:
                $orders = Order::whereUserId($user->id)->paginated();
                break;

            default:
                $orders = [];
                break;
        }

        return $this->ok(OrderResource::collection($orders));
    }

    /**
     * Create Order.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $shop = Shop::findOrFail($request->shop_id);
        $cart = $request->user()->getCart($shop->id);

        if (empty($cart)) {
            return $this->error(\null, 'Your cart for this shop is empty.');
        }

        $order = $request->user()
            ->orders()
            ->create($request->validated())
            ->refresh();

        $order->items()->createMany(
            \collect($cart)
                ->map(function ($cartItem) {
                    return [
                        'product_id' => $cartItem['id'],
                        'comment'    => $cartItem['comment'],
                        'quantity'   => $cartItem['quantity'],
                    ];
                })
                ->toArray()
        );

        $request->user()->setCart($shop->id, []);

        return $this->ok(
            new OrderResource($order),
            JsonResponse::HTTP_CREATED,
            'Order created.',
            [
                'Location' => \action([$this::class, 'show'], $order),
            ]
        );
    }

    /**
     * Get Order.
     */
    public function show(Order $order): JsonResponse
    {
        return $this->ok(
            new OrderResource($order->load(['user', 'shop', 'items'])),
        );
    }
}
