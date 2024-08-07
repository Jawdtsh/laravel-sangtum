<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RequireEmailRequest;
use App\Http\Requests\verifyCodeRequest;
use App\Models\User;
use App\Traits\ApiResponseHandlerTrait;
use App\Traits\FileHandlerTrait;
use App\Traits\VerifiableCodeTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use VerifiableCodeTrait ,FileHandlerTrait ,ApiResponseHandlerTrait;


    public function register(RegisterRequest $request): JsonResponse
    {

        // Handle file uploads
        $profilePhoto = $request->file('profile_photo');
        $certificate = $request->file('certificate');

        $profilePhotoPath = $profilePhoto?->storeAs('profile_photos', $this->GenerateUniqueFileNameWithUUID($profilePhoto), 'public');
        $certificatePath = $certificate?->storeAs('certificates', $this->GenerateUniqueFileNameWithHash($certificate), 'public');


        // Create a new user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'profile_photo' => $profilePhotoPath,
            'certificate' => $certificatePath,
            'password' => Hash::make($request->password),
            'email_verified' => false,
            'email_verification_code' => null
        ]);

        return $this->SendCodeToEmail(
            $user,
            'تم تسجيل حسابك بنجاح. لتاكيد الايميل نرجو التحقق من البريد الوارد على الايميل الخاص بك.'
        );

    }





    public function ResendVerificationCode(RequireEmailRequest $email): JsonResponse
    {

       if($user = User::where('email', $email['email'])->first()) {
            if($user->email_verified) {
                return $this->errorResponse('يبدو بان حسابك مؤكد بالفعل');
            }
           return $this->SendCodeToEmail($user, 'تمت عمليت العادة ارسال كود التاكيد بنجاح');
       }
        return $this->errorResponse('لم يتم تجسيل هذا الحساب في موقعنا بعد');

    }




    public function login(LoginRequest $request): JsonResponse
    {
        $identifier = $request->identifier;
        $password = $request->password;

        $user = User::where('email', $identifier)->orWhere('phone_number', $identifier)->first();

        // Check the user and his password if they are correct.
        if (!$user || !Hash::check($password, $user->password)) {
            return $this->errorResponse('بيانات تسجيل الدخول غير صحيحة', 401);
        }

        // Verify email confirmation
        if (!$user->email_verified) {
            return $this->errorResponse('يبدو انك لم تقم بتاكيد حسابك حتى الان, ارجو ان تقوم بتاكيد حسابك لتسجيل الدخول بنجاح', 403);
        }

        return $this->SendCodeToEmail($user,
            'لمتابعى تسجيل الدخول نرجو مراجعة الايميل الخاص بك لتاكيد ال 2fa'
            );
    }





    public function Resend2FaCode(RequireEmailRequest $email): JsonResponse
    {
        if($user = User::where('email', $email['email'])->first()) {
            if(!$user->email_verified) {
                return $this->errorResponse('يبدو بان حسابك  لم يتم تاكيده حتى الان تروج منك تايكد حسابك قبل طلب كود 2fa');
            }
            return $this->SendCodeToEmail($user, 'تمت عمليت اعادة ارسال كود 2fa بنجاح');
        }
        return $this->errorResponse('لم يتم تجسيل هذا الحساب في موقعنا بعد');
    }




    public function Verify2FaCode(verifyCodeRequest $request): JsonResponse
    {

        $ipAddress = request()->ip();


        $cacheData = Cache::get($ipAddress);


        if ($cacheData && isset($cacheData['email']) && isset($cacheData['code'])) {
            if ($cacheData['email'] === $request['email']) {
                if ($user = User::where('email', $request['email'])->first()) {
                    if (!$user->email_verified) {
                        return $this->errorResponse('يبدو أن حسابك لم يفعل بعد، نرجو تأكيد حسابك أولاً.');
                    }

                    if ($cacheData['code'] === $request['email_verification_code']) {
                        $token = $user->createToken('Personal Access Token', ['*'], now()->addMinutes(10))->plainTextToken;
                        return $this->successResponse('your token is: '.$token, 'لقد تم تأكيد الحساب');
                    }

                    return $this->errorResponse('يبدو أن الكود الذي أدخلته غير صحيح');
                }

                return $this->errorResponse('لم يتم العثور على حساب لدينا لهذا البريد الإلكتروني');
            }
        }

        return $this->errorResponse('لم يتم العثور على كود تحقق لهذا العنوان IP');
    }







//    public function Verify2FaCode(verifyCodeRequest $request): JsonResponse
//    {
//
//        if($user = User::where('email', $request['email'])->first()){
//
//            if(!$user->email_verified){
//                return $this->errorResponse('يبدو بان حسباك لم يفعل بعد نرجو ان تقوم بتاكيد حسابك ثم تاكيد حسابك ب 2fa');
//            }
//
//            if ($user->email_verification_code === $request['email_verification_code']) {
//
//                $token = $user->createToken('Personal Access Token', ['*'], now()->addMinutes(10))->plainTextToken;
//                return $this->successResponse('your token is : '.$token,'لقد تم تاكيد الحساب ');
//
//            }
//
//            return $this->errorResponse('يبدو بان الكود الذي ادخلته غير صحيح');
//
//        }
//        return $this->errorResponse('لم يتم العثور على حساب لدينا لهذا الايميل');
//    }






    public function logout(Request $request): JsonResponse
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return $this->successMessage('تم تسجيل الخروج بنجاح');
    }





    public function refreshToken(Request $request): JsonResponse
    {
        if (Auth::user()) {
            $user = $request->user();
            $user->currentAccessToken()->delete();
            $newToken = $user->createToken('Personal Access Token', ['*'], now()->addMinutes(20))->plainTextToken;

            return $this->successResponse(['token' => $newToken], 'Token refreshed successfully', 201);
        }
        return $this->errorResponse('يبدو بانك لم تقم بتسجيل الدخول', 401);
    }




//    public function verifyEmailCode(verifyCodeRequest $request): JsonResponse
//    {
//
//        if($user = User::where('email', $request['email'])->first()){
//
//            if($user->email_verified ){
//                return $this->errorResponse('يبدو بان حسابك مفعل بالفعل');
//            }
//
//            if ($user->email_verification_code === $request['email_verification_code']) {
//                $user->email_verified = true;
//                $user->save();
//
//                return $this->successMessage('لقد تم تاكيد الحساب ');
//            }
//            return $this->errorResponse('يبدو بان الكود الذي ادخلته غير صحيح');
//        }
//
//        return $this->errorResponse('لم يتم العثور على حساب لدينا لهذا الايميل');
//
//    }



    public function verifyEmailCode(verifyCodeRequest $request): JsonResponse
    {

        $ipAddress = request()->ip();


        $cacheData = Cache::get($ipAddress);

        if (isset($cacheData['email'], $cacheData['code']) && $cacheData && $cacheData['email'] === $request['email']) {
            if ($user = User::where('email', $request['email'])->first()) {
                if ($user->email_verified) {
                    return $this->errorResponse('يبدو أن حسابك مفعل بالفعل');
                }

                if ($cacheData['code'] === $request['email_verification_code']) {
                    $user->email_verified = true;
                    $user->save();

                    // قم بإزالة البيانات من الكاش بعد التحقق الناجح
                    Cache::forget($ipAddress);

                    return $this->successMessage('لقد تم تأكيد الحساب');
                }
                return $this->errorResponse('يبدو أن الكود الذي أدخلته غير صحيح');
            }

            return $this->errorResponse('لم يتم العثور على حساب لدينا لهذا الايميل');
        }

        return $this->errorResponse('لم يتم العثور على كود تحقق لهذا العنوان IP');
    }





}
