<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait SendsResponseTrait
{
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

        if ($data instanceof JsonResource) {
            return $data->additional($body)
                ->response()
                ->setStatusCode($status)
                ->withHeaders($headers);
        }

        if ($data !== \null) {
            $body['data'] = $data;
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
