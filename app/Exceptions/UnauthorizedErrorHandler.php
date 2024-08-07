<?php

namespace App\Exceptions;

use App\interface\ErrorHandlerInterface;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedErrorHandler implements ErrorHandlerInterface
{
    public function handle(Request $request, Throwable $exception): Response
    {
        return response()->json(['error' => 'Unauthorized', 'message' => 'You are not authorized to access this page.'], 401);
    }
}
