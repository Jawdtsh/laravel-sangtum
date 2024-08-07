<?php

namespace App\Exceptions;

use App\interface\ErrorHandlerInterface;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequestsErrorHandler implements ErrorHandlerInterface
{
    public function handle(Request $request, Throwable $exception): Response
    {
        return response()->json(['error' => 'Too Many Requests', 'message' => 'You have made too many requests in a short period of time. Please try again later.'], 429);
    }
}
