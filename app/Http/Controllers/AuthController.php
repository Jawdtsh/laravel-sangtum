<?php

namespace App\Http\Controllers;

use App\Http\Requests\{
    LoginRequest,
    RegisterRequest,
    RequireEmailRequest,
    verifyCodeRequest,
};
use Illuminate\Support\Facades\{
    Cache,
    Hash
};
use Illuminate\Http\{
    JsonResponse,
    Request};
use App\Traits\{
    ApiResponseHandlerTrait,
    FileHandlerTrait,
    VerifiableCodeTrait
};
use App\Models\User;

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
            'تم تسجيل حسابك بنجاح. لتاكيد الايميل نرجو التحقق من البريد الوارد على الايميل الخاص بك.'
            ,$user
        );

    }




    public function ResendVerificationCode(RequireEmailRequest $email): JsonResponse
    {

        $user = User::where('email', $email['email'])->first();

        if($user->email_verified) {
            return $this->errorResponse('يبدو بان حسابك مؤكد بالفعل',400);
        }

        return $this->SendCodeToEmail('تمت عمليت العادة ارسال كود التاكيد بنجاح',$user);

    }




    public function login(LoginRequest $request): JsonResponse
    {
        $identifier = $request->identifier;
        $password = $request->password;

        $user = User::where('email', $identifier)->orWhere('phone_number', $identifier)->first();


        if (!$user || !Hash::check($password, $user->password)) {
            return $this->errorResponse('بيانات تسجيل الدخول غير صحيحة', 401);
        }


        if (!$user->email_verified) {
            return $this->errorResponse('يبدو انك لم تقم بتاكيد حسابك حتى الان, ارجو ان تقوم بتاكيد حسابك لتسجيل الدخول بنجاح', 403);
        }

        return $this->SendCodeToEmail('لمتابعى تسجيل الدخول نرجو مراجعة الايميل الخاص بك لتاكيد ال 2fa',$user);
    }





    public function Resend2FaCode(RequireEmailRequest $email): JsonResponse
    {
        $user = User::where('email', $email['email'])->first();

        if(!$user->email_verified) {
            return $this->errorResponse('يبدو بان حسابك  لم يتم تاكيده حتى الان نروج منك تايكد حسابك قبل طلب كود 2fa',403);
        }
        return $this->SendCodeToEmail('تمت عمليت اعادة ارسال كود 2fa بنجاح',$user);
    }





    public function Verify2FaCode(verifyCodeRequest $request): JsonResponse
    {
        $ipAddress = request()->ip();
        $cacheData = Cache::get($ipAddress);

        if (!($cacheData  && isset($cacheData['email'], $cacheData['code']))) {
            return $this->errorResponse('يبدو ان الكود قد انتهت صلاحيته',404);
        }

        $user = User::where('email', $request['email'])->first();

        if (!$user->email_verified) {
            return $this->errorResponse('يبدو أن حسابك لم يفعل بعد، نرجو تأكيد حسابك أولاً.',403);
        }

        if (!($cacheData['code'] === $request['email_verification_code']&& $cacheData['email'] === $request['email'])) {
            return $this->errorResponse('يبدو أن الكود الذي أدخلته غير صحيح',400);
        }

        $token = $user->createToken('Personal Access Token', ['*'], now()->addMinutes(10))->plainTextToken;
        return $this->successResponse('your token is: '.$token, 'لقد تم تأكيد الحساب');
    }






    public function logout(Request $request): JsonResponse
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return $this->successMessage('تم تسجيل الخروج بنجاح');
    }





    public function refreshToken(Request $request): JsonResponse
    {

        $user = $request->user();
        $user->currentAccessToken()->delete();
        $newToken = $user->createToken('Personal Access Token', ['*'], now()->addMinutes(20))->plainTextToken;
        return $this->successResponse(['token' => $newToken], 'Token refreshed successfully', 201);

    }





    public function verifyEmailCode(verifyCodeRequest $request): JsonResponse
    {

        ($user = User::where('email', $request['email'])->first());
        if($user->email_verified) {
            return $this->errorResponse('يبدو بان حسابك مفعل بالفعل',400);
        }

        $ipAddress = request()->ip();
        $cacheData = Cache::get($ipAddress);

        if (!( $cacheData && isset($cacheData['email'], $cacheData['code']) )) {
            return $this->errorResponse('يبدو انك لم تطلب كود التحقق او ان كود التحقق قد انتهت صلاحيته',404);
        }

        if (!($cacheData['code'] === $request['email_verification_code'] && $cacheData['email'] === $request['email'])) {
            return $this->errorResponse('يبدو أن الكود الذي أدخلته غير صحيح',400);
        }

        $user->email_verified = true;
        $user->save();

            Cache::forget($ipAddress);

            return $this->successMessage('لقد تم تأكيد الحساب');
    }

}
