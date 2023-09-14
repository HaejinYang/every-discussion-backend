<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($e->getModel()));

            return $this->error("{$model}이 존재하지 않습니다", Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof ValidationException) {
            return $this->error($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof AuthorizationException) {
            return $this->error($e->getMessage(), Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof RouteNotFoundException) {
            return $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        if ($e instanceof QueryException) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (config('app.debug')) {
            return parent::render($request, $e);
        }

        return $this->error('예기치 못한 예외', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
