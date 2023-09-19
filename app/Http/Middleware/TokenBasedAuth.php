<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TokenBasedAuth
{
    use ApiResponser;

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if (!auth('sanctum')->check()) {
            return $this->error('인증이 필요합니다', Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
