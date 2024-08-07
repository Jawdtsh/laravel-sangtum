<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class verifyCodeRequest extends FormRequest
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
            'email'                    =>   'required|email|exists:users,email',
            'email_verification_code'  =>   'required|size:6',
        ];
    }


    public function messages(): array
    {
        return [
            'email.required' => 'الإيميل مطلوب.',
            'email.email' => 'يجب أن يكون الإيميل صالحًا.',
            'email.exists' => 'الإيميل غير موجود في النظام.',
            'email_verification_code.required' => 'كود التحقق مطلوب.',
            'email_verification_code.size' => 'يجب ان يحتوي الكود على 6 عناصر',
        ];
    }
}
