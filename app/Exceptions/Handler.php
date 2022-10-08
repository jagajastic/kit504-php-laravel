<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
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

            if ($e instanceof NotFoundHttpException) {
                return response()->json(
                    [
                        'ok'      => \false,
                        'message' => 'Record not found.',
                    ],
                    JsonResponse::HTTP_NOT_FOUND
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

                return response()->json(
                    [
                        'ok'      => \false,
                        'message' => 'Validation error.',
                        'errors'  => $errors,
                    ],
                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                );
            }

            return response()->json(
                [
                    'ok'      => \false,
                    'message' => 'Server error.',
                ],
                $this->isHttpException($e) ? $e->getStatusCode() : JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                $this->isHttpException($e) ? $e->getHeaders() : [],
            );
        });
    }
}
