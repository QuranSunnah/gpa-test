<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ContactUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users to make this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rule = Auth::guard('api')->check() ? 'nullable' : 'required';

        return [
            'full_name' => [
                $rule,
                'string',
                'max:255',
            ],
            'email' => [
                $rule,
                'email',
                'max:255',
            ],
            'phone' => [
                $rule,
                'regex:/^(\+8801[1-9][0-9]{8}|8801[1-9][0-9]{8}|01[1-9][0-9]{8})$/',
            ],
            'message' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number format is invalid.',
        ];
    }
}
