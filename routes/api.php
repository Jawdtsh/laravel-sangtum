<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware' => ['auth:sanctum']], static function () {

    Route::apiResource('/product', ProductController::class);
    Route::apiResource('/categories', CategoryController::class);

    Route::post('/orders/{order}', [OrderController::class, 'update']);
    Route::apiResource('/orders', OrderController::class);
});



require __DIR__ . '/ApiTestException.php';
require __DIR__ . '/AuthApi.php';

Route::fallback(static function () {
    return response()->json(['message' => 'Not Found.'], 404);
});


