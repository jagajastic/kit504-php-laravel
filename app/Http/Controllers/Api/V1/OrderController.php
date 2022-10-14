<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Shop;
use App\Models\Order;
use App\Enums\UserType;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OrderResource;
use App\Http\Requests\Api\V1\Order\StoreRequest;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($request) {
            $shop = Shop::findOrFail($request->shop_id);
            $cart = $request->user()->getCart($shop->id);

            if (empty($cart)) {
                return $this->error(
                    \null,
                    'Your cart for this shop is empty.'
                );
            }

            if ($shop->opening_hours->isClosed()) {
                return $this->error(
                    \null,
                    'This shop has not opened.'
                );
            }

            $totalPrice     = 0;
            $orderItems     = [];
            $accountBalance = $request->user()->account_balance;

            foreach ($cart as $cartItem) {
                $product = Product::find($cartItem['id']);

                if ($product !== \null) {
                    $orderItems[] = [
                        'product_id'    => $product->id,
                        'product_price' => $product->price,
                        'product_name'  => $product->name,
                        'product_image' => $product->getRawOriginal('image'),
                        'comment'       => $cartItem['comment'],
                        'quantity'      => $cartItem['quantity'],
                    ];

                    $totalPrice += $product->price * $cartItem['quantity'];
                }
            }

            if ($accountBalance < $totalPrice) {
                return $this->error(
                    \null,
                    'Insufficient funds.'
                );
            }

            $request->user()->update([
                'account_balance' => $accountBalance - $totalPrice,
            ]);

            $orderData = $request->safe()
                ->collect()
                ->put('total_price', $totalPrice)
                ->toArray();

            $order = $request->user()
                ->orders()
                ->create($orderData)
                ->refresh();

            $order->items()->createMany($orderItems);

            $request->user()->setCart($shop->id, []);

            return $this->ok(
                new OrderResource($order),
                JsonResponse::HTTP_CREATED,
                'Order created.',
                [
                    'Location' => \action([$this::class, 'show'], $order),
                ]
            );
        });
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
