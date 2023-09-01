<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponser
{
    protected function success($data, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['data' => $data], $code);
    }

    protected function error(string $msg, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json(['error' => $msg, 'code' => $code], $code);
    }

    protected function showAll($data, $code = Response::HTTP_OK): JsonResponse
    {
        return $this->success($data, $code);
    }

    protected function showOne($data, $code = Response::HTTP_OK): JsonResponse
    {
        return $this->success($data, $code);
    }

    protected function showMessage(string $msg, $code = Response::HTTP_OK): JsonResponse
    {
        return $this->success($msg, $code);
    }
}
