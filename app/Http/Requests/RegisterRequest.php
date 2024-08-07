<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username'      => 'required|string|max:50',
            'email'         => 'required|string|email|max:255|unique:users',
            'phone_number'  => 'required|string|max:20|unique:users',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|unique:users',
            'certificate'   => 'nullable|file|mimes:pdf|max:10000|unique:users',
            'password'      => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'password_confirmation' => 'required|same:password'
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'اسم المستخدم مطلوب.',
            'username.string'   => 'اسم المستخدم يجب أن يكون نصًا.',
            'username.max'      => 'اسم المستخدم يجب ألا يتجاوز 50 حرفًا.',
            'username.unique'   => 'اسم المستخدم موجود بالفعل.',

            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string'   => 'البريد الإلكتروني يجب أن يكون نصًا.',
            'email.email'    => 'يجب إدخال بريد إلكتروني صالح.',
            'email.max'      => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفًا.',
            'email.unique'   => 'البريد الإلكتروني موجود بالفعل.',

            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'phone_number.string'   => 'رقم الهاتف يجب أن يكون نصًا.',
            'phone_number.max'      => 'رقم الهاتف يجب ألا يتجاوز 20 حرفًا.',
            'phone_number.unique'   => 'رقم الهاتف موجود بالفعل.',

            'profile_photo.image' => 'الصورة الشخصية يجب أن تكون صورة.',
            'profile_photo.mimes' => 'الصورة الشخصية يجب أن تكون من نوع jpeg أو png أو jpg.',
            'profile_photo.max'   => 'الصورة الشخصية يجب ألا يتجاوز حجمها 2048 كيلوبايت.',

            'certificate.file'  => 'الشهادة يجب أن تكون ملفًا.',
            'certificate.mimes' => 'الشهادة يجب أن تكون ملف PDF.',
            'certificate.max'   => 'حجم ملف الشهادة يجب ألا يتجاوز 10MB.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.string'   => 'كلمة المرور يجب أن تكون نصًا.',
            'password.min'      => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
            'password.regex'    => 'كلمة المرور يجب أن تحتوي على حرف كبير وحرف صغير ورقم ورمز خاص.',

            'password_confirmation.required'  => 'تأكيد كلمة المرور مطلوب.',
            'password_confirmation.confirmed' => 'تأكيد كلمة المرور لا يتطابق مع كلمة المرور.'
        ];
    }
}
