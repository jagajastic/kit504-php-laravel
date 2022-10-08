<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use DispatchesJobs;
    use ValidatesRequests;
    use AuthorizesRequests;

    public function ok(
        $data = \null,
        $status = JsonResponse::HTTP_OK,
        $message = \null,
        $headers = []
    ): JsonResponse {
        $body = [
            'ok'   => \true,
        ];

        if ($message !== \null) {
            $body['message'] = $message;
        }

        if ($data !== \null) {
            $body['data'] = $data;
        }

        if ($status === JsonResponse::HTTP_NO_CONTENT) {
            $body = '';
        }

        return new JsonResponse($body, $status, $headers);
    }

    public function error(
        $errors = \null,
        $message = \null,
        $status = JsonResponse::HTTP_BAD_REQUEST,
        $headers = []
    ): JsonResponse {
        $body = [
            'ok' => \false,
        ];

        if ($message !== \null) {
            $body['message'] = $message;
        }

        if ($errors !== \null) {
            $body['errors'] = $errors;
        }

        return new JsonResponse($body, $status, $headers);
    }
}
