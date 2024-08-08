<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/verify-email',[AuthController::class, 'verifyEmailCode']);
Route::post('/Verify2FA',[AuthController::class, 'Verify2FaCode']);
Route::post('/resend-verification-code',[AuthController::class, 'ResendVerificationCode']);
Route::post('/resend-2fa-code',[AuthController::class, 'Resend2FaCode']);

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/refresh-token',[AuthController::class, 'refreshToken'])->middleware('auth:sanctum');





require __DIR__ . '/ApiTestException.php';

Route::fallback(static function () {
    return response()->json(['message' => 'Not Found.'], 404);
});


