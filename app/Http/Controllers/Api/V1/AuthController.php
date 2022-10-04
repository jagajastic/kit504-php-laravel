<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\JsonApi\V1\Auth\LoginRequest;
use App\JsonApi\V1\Auth\RegisterRequest;
use LaravelJsonApi\Core\Document\Error;
use Illuminate\Contracts\Support\Responsable;
use LaravelJsonApi\Core\Responses\DataResponse;

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
    public function login(LoginRequest $request): Responsable
    {
        $user = User::getUserBy($request->email, $request->password);

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
    public function register(RegisterRequest $request): Responsable
    {
        $user = User::create($request->validated());

        $user->refresh();

        return $this->authResponse($user);
    }

    /**
     * Get authentication response with "user" and "token" properties.
     */
    public function authResponse(User $user): Responsable
    {
        $uuid = Str::orderedUuid()->toString();

        $user->apiTokens()->create([
            'value' => $uuid,
        ]);

        $data = [
            'user'  => $user,
            'token' => $uuid,
        ];

        return DataResponse::make($data)->didCreate();
    }
}
