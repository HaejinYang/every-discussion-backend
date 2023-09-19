<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RetrieveUserData
{
    use ApiResponser;

    public function handle(Request $request, Closure $next)
    {
        $user = auth('sanctum')->user();
        if (is_null($user)) {
            return $this->error('유저가 존재하지 않습니다.', Response::HTTP_NOT_FOUND);
        }

        $request->merge(['user' => $user]);

        return $next($request);
    }
}
