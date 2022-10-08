<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Enums\UserType;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\JsonApi\V1\Auth\LoginRequest;
use LaravelJsonApi\Core\Document\Error;
use App\JsonApi\V1\Auth\RegisterRequest;
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
    public function login(LoginRequest $request): JsonResponse|Error
    {
        $email    = $request->input('data.attributes.email');
        $password = $request->input('data.attributes.password');
        $user     = User::getUserBy($email, $password);

        if ($user === \null) {
            return Error::fromArray([
                'status' => 401,
                'detail' => 'Email or password does not match.',
            ]);
        }

        return $this->authResponse($user);
    }

    /**
     * Register a user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->input('data.attributes'));

        $user->refresh();

        return $this->authResponse($user);
    }

    /**
     * Get authentication response with "user" and "token" properties.
     */
    public function authResponse(User $user): JsonResponse
    {
        $uuid = Str::orderedUuid()->toString();

        $user->apiTokens()->create([
            'value' => $uuid,
        ]);

        $hidden = ['id'];

        $included = [
            'api_token' => $uuid,
        ];

        if (\in_array($user->type, UserType::normalUsers())) {
            $hidden[]                        = 'shop_id';
            $included['account_balance_usd'] = $user->account_balance_usd;
        } else {
            $hidden[] = 'account_balance';
        }

        $attributes = collect($user)
            ->except($hidden)
            ->merge($included)
            ->toArray();

        $data = [
            'jsonapi' => [
                'version' => '1.0',
            ],
            'data' => [
                'type'       => 'auth',
                'id'         => $user->id,
                'attributes' => $attributes,
            ],
        ];

        return new JsonResponse($data, JsonResponse::HTTP_CREATED);
    }
}
