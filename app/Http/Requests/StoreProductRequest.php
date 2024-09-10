<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|boolean',
            'discount' => 'nullable|numeric|min:0|max:100',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
        ];
    }

    /**
     * Customize the error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'name.string' => 'The product name must be a string.',
            'name.max' => 'The product name may not be greater than 255 characters.',

            'description.string' => 'The description must be a string.',

            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',

            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The quantity must be an integer.',
            'quantity.min' => 'The quantity must be at least 0.',

            'status.required' => 'The status field is required.',
            'status.boolean' => 'The status must be true or false.',

            'discount.numeric' => 'The discount must be a number.',
            'discount.min' => 'The discount must be at least 0.',
            'discount.max' => 'The discount may not be greater than 100.',

            'weight.numeric' => 'The weight must be a number.',
            'weight.min' => 'The weight must be at least 0.',

            'dimensions.string' => 'The dimensions must be a string.',
            'dimensions.max' => 'The dimensions may not be greater than 255 characters.',
        ];
    }
}

