<?php

namespace App\Exceptions;

use App\interface\ErrorHandlerInterface;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternalServerErrorHandler implements ErrorHandlerInterface
{
    public function handle(Request $request, Throwable $exception): Response
    {
        return response()->json(['error' => 'Internal Server Error', 'message' => 'Something went wrong on our servers.'], 500);
    }
}
