<?php

namespace App\Services;

use App\Models\User;
use App\Traits\FileHandlerTrait;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use FileHandlerTrait;
    public function getUserDetails($userId){}

    public function CreateUser($request){

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
        return $user;
    }


    public function UpdateUser($userId){}
    public function DeleteUser($userId){}

}
