<?php

namespace App\interface;

use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

interface ErrorHandlerInterface
{
    public function handle(Request $request, Throwable $exception): Response;
}
