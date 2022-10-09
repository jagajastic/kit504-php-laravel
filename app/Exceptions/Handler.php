<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\SendsResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use SendsResponseTrait;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'current_password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e, Request $request) {
            if (!$request->is('api/*')) {
                return;
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return $this->error(
                    \null,
                    $this->getStatusText(
                        JsonResponse::HTTP_METHOD_NOT_ALLOWED,
                        $e
                    ),
                    JsonResponse::HTTP_METHOD_NOT_ALLOWED,
                );
            }

            if ($e instanceof AuthenticationException) {
                return $this->error(
                    \null,
                    $this->getStatusText(
                        JsonResponse::HTTP_UNAUTHORIZED,
                        $e
                    ),
                    JsonResponse::HTTP_UNAUTHORIZED,
                );
            }

            if ($e instanceof NotFoundHttpException) {
                return $this->error(
                    \null,
                    $this->getStatusText(
                        JsonResponse::HTTP_NOT_FOUND,
                        $e
                    ),
                    JsonResponse::HTTP_NOT_FOUND,
                );
            }

            if ($e instanceof ValidationException) {
                $errors = [];
                $errorBag = $e->validator->errors();
                $keys = $errorBag->keys();

                foreach ($keys as $key) {
                    Arr::set(
                        $errors,
                        $key,
                        $errorBag->first($key)
                    );
                }

                return $this->error(
                    $errors,
                    $this->getStatusText(
                        JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                        $e
                    ),
                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                );
            }

            if ($e instanceof HttpException) {
                return $this->error(
                    \null,
                    $this->getStatusText($e->getStatusCode(), $e),
                    $e->getStatusCode(),
                );
            }
        });
    }

    /**
     * Get status message for HTTP code.
     */
    public function getStatusText(int $code, Throwable $e): string
    {
        switch ($code) {
            case JsonResponse::HTTP_METHOD_NOT_ALLOWED:
                return 'HTTP method is invalid.';
            case JsonResponse::HTTP_NOT_FOUND:
                return 'Record not found.';
            case JsonResponse::HTTP_FORBIDDEN:
                return 'Permission denied.';
            case JsonResponse::HTTP_UNAUTHORIZED:
                return 'Unauthenticated';
            case JsonResponse::HTTP_UNPROCESSABLE_ENTITY:
                return 'Validation errors.';
            default:
                return empty($e->getMessage()) ? 'Server error.' : $e->getMessage();
        }
    }
}
