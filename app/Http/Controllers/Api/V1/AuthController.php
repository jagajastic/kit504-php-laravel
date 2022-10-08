<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegisterRequest;
use function collect;

class AuthController extends Controller
{
    /**
     * Create [AuthController] instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register']);
    }

    /**
     * Login a user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user     = User::getUserBy($request->email, $request->password);

        if ($user === \null) {
            return $this->error(
                \null,
                'Email or password does not match.',
                JsonResponse::HTTP_UNAUTHORIZED,
            );
        }

        return $this->authResponse($user);
    }

    /**
     * Register a user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        $user->refresh();

        return $this->authResponse($user);
    }

    /**
     * Get authentication response with "user" and "token" properties.
     */
    public function authResponse(User $user): JsonResponse
    {
        $uuid = Str::orderedUuid()->toString();

        $user->apiTokens()->create(['value' => $uuid]);

        $hidden = [];

        $included = [
            'api_token' => $uuid,
        ];

        $is_normal_user = \in_array($user->type, UserType::normalUsers());

        if ($is_normal_user) {
            $included['account_balance_usd'] = $user->account_balance_usd;
        } else {
            $hidden[] = 'account_balance';
        }

        if ($is_normal_user || $user->type === UserType::DIRECTOR) {
            $hidden[] = 'shop_id';
        }

        $data = collect($user)
            ->except($hidden)
            ->merge($included)
            ->toArray();

        return $this->ok($data, JsonResponse::HTTP_CREATED);
    }
}
