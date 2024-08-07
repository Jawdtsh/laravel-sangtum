<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $e, Request $request) {
            return $this->handleException($request, $e);
        });
    }

    /**
     * @throws Throwable
     */
    protected function handleException(Request $request, Throwable $exception)
    {
         Log::info('Handling exception of type: ' . get_class($exception));

        $handlers = [
            AuthenticationException::class => UnauthorizedErrorHandler::class,
            AuthorizationException::class => ForbiddenErrorHandler::class,
            NotFoundHttpException::class => NotFoundErrorHandler::class,
            ThrottleRequestsException::class => ThrottleRequestsErrorHandler::class,
            HttpException::class => InternalServerErrorHandler::class,
            QueryException::class => QueryErrorHandler::class,
        ];

        foreach ($handlers as $type => $handler) {
            if ($exception instanceof $type) {
                Log::info('Found handler for: ' . get_class($exception));
                return App::make($handler)->handle($request, $exception);
            }
        }

               Log::info('No specific handler found, using parent render method');
        return $this->render($request, $exception);
    }
}
