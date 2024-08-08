<?php

namespace App\Traits;

use App\Events\VerifyUserEvent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;



trait VerifiableCodeTrait
{
    /**

     */
//    public static function generateCode($length = 6): string
//    {
//        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//        $charactersLength = strlen($characters);
//        $randomString = '';
//
//        for ($i = 0; $i < $length; $i++) {
//            $randomString .= $characters[random_int(0, $charactersLength - 1)];
//        }
//
//        return $randomString;
//    }



    public function generateCode(): string
    {
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';

        $code = [
            $upper[random_int(0, strlen($upper) - 1)],
            $lower[random_int(0, strlen($lower) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)]
        ];

        $allCharacters = $upper . $lower . $numbers;
        for ($i = 3; $i < 6; $i++) {
            $code[] = $allCharacters[random_int(0, strlen($allCharacters) - 1)];
        }

        shuffle($code);

        return implode('', $code);
    }






    public function SendCodeToEmail( string $success, User $user = null): JsonResponse
    {
        if (is_null($user)) {
            return $this->errorResponse('حدث خطاء في الوصول الى المستخدم', 401);
        }

        $code = $this->generateCode();

            $ipAddress = request()->ip();

            Cache::put($ipAddress, [
                'email' => $user->email,
                'code' => $code
            ], now()->addMinutes(10));

            event(new VerifyUserEvent($user, $code));

            return $this->successMessage($success);
    }

}
