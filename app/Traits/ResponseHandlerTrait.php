<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseHandlerTrait
{


    public function successWithDataResponse($data = [], $message = 'success', $code = 200): JsonResponse
    {
        return response()->json([ 'message' => $message,'data' => $data],$code);
    }
    public function successResponse( $message = 'success', $code = 200): JsonResponse
    {
        return response()->json([ 'message' => $message],$code);
    }
    public function errorResponse($message = 'error', $code = 400): JsonResponse
    {
        return response()->json([ 'message' => $message,],$code);
    }
}
