<?php

namespace App\Exceptions;

use App\interface\ErrorHandlerInterface;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotFoundErrorHandler implements ErrorHandlerInterface
{
    public function handle(Request $request, Throwable $exception): Response
    {
        return response()->json(['error' => 'Not Found', 'message' => 'The page you are looking for could not be found.'], 404);
    }
}
