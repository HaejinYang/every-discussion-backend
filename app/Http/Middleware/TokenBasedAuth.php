<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TokenBasedAuth
{
    use ApiResponser;

    public function handle(Request $request, Closure $next)
    {
        if (!auth('sanctum')->check()) {
            return $this->error('인증이 필요합니다', Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
