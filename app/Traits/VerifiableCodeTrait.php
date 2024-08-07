<?php

namespace App\Traits;

use App\Events\VerifyUserEvent;
//use App\Http\Requests\EmailRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
//use Random\RandomException;

trait VerifiableCodeTrait
{
    /**
//     * @throws RandomException
     */
    public static function generateCode($length = 6): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }




//    public function SendCodeToEmail(User $user = null , string $success ): JsonResponse
//    {
//
//        if(!is_null($user)){
//
//            $code = self::generateCode();
//
//            event(new VerifyUserEvent($user, $code));
//            $user->update(['email_verification_code'=>$code]);
//
//            return $this->successMessage($success);
//        }
//
//        return $this->errorResponse('حدث خطاء في الوصول الى المستخدم', 401);
//    }


    public function SendCodeToEmail(User $user = null , string $success ): JsonResponse
    {
        if (!is_null($user)) {
            $code = self::generateCode();


            $ipAddress = request()->ip();



            Cache::put($ipAddress, [
                'email' => $user->email,
                'code' => $code
            ], now()->addMinutes(10));

//            dd(Cache::get($ipAddress));
            event(new VerifyUserEvent($user, $code));

            return $this->successMessage($success);
        }

        return $this->errorResponse('حدث خطاء في الوصول الى المستخدم', 401);
    }

}
