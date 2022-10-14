<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create [AuthController] Instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register']);
    }

    /**
     * Login.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::getUserBy($request->email, $request->password);

        if ($user === \null) {
            return $this->error(
                \null,
                'Email or password does not match.',
                JsonResponse::HTTP_UNAUTHORIZED,
            );
        }

        return $this->ok(new UserResource($user), JsonResponse::HTTP_CREATED);
    }

    /**
     * Register.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated())->refresh();

        return $this->ok(new UserResource($user), JsonResponse::HTTP_CREATED);
    }

    /**
     * User.
     */
    public function user(Request $request): JsonResponse
    {
        return $this->ok(new UserResource($request->user(), \false));
    }
}
