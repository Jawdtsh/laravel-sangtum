<?php

namespace App\Exceptions;

use App\interface\ErrorHandlerInterface;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForbiddenErrorHandler implements ErrorHandlerInterface
{
    public function handle(Request $request, Throwable $exception): Response
    {
        return response()->json(['error' => 'Forbidden', 'message' => 'You do not have permission to access this page.'], 403);
    }
}
