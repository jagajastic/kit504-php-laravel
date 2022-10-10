<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\SendsResponseTrait;

class UserTypeMiddleware
{
    use SendsResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string ...$userTypes
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$userTypes)
    {
        $userType = \optional($request->user())->type;

        \abort_if(
            !\in_array($userType, $userTypes),
            JsonResponse::HTTP_FORBIDDEN
        );

        return $next($request);
    }

    /**
     * Construct middleware string from array.
     */
    public static function make(array $userTypes): string
    {
        return 'auth.type:' . \implode(',', $userTypes);
    }
}
