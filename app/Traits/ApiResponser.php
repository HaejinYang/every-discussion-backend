<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    // JSON_UNESCAPED_UNICODE : Encode multibyte Unicode characters literally (default is to escape as \uXXXX).
    protected function error(string $msg, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        if (config('app.enable_debug_bar')) {
            var_dump($msg);
            return;
        }

        return response()->json(['error' => $msg, 'code' => $code], $code, [], JSON_UNESCAPED_UNICODE);
    }

    protected function showAll($data, $code = Response::HTTP_OK)
    {
        if (config('app.enable_debug_bar')) {
            var_dump($data);
            return;
        }

        return $this->success($data, $code, [], JSON_UNESCAPED_UNICODE);
    }

    protected function success($data, int $code = Response::HTTP_OK)
    {
        if (config('app.enable_debug_bar')) {
            var_dump($data);
            return;
        }

        return response()->json(['data' => $data], $code, [], JSON_UNESCAPED_UNICODE);
    }

    protected function showOne($data, $code = Response::HTTP_OK)
    {
        if (config('app.enable_debug_bar')) {
            var_dump($data);
            return;
        }

        return $this->success($data, $code, [], JSON_UNESCAPED_UNICODE);
    }

    protected function showMessage(string $msg, $code = Response::HTTP_OK)
    {
        if (config('app.enable_debug_bar')) {
            var_dump($msg);
            return;
        }

        return $this->success($msg, $code, [], JSON_UNESCAPED_UNICODE);
    }
}
