<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationCompleteRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'identityType' => 'required|string|in:email,phone',
            'email' => [
                'prohibited_unless:identityType,email',
                'required_unless:phone,null',
                'string',
                'email:rfc,dns',
                'max:255',
                Rule::exists('users', 'email')->where('is_verified', 0)->whereNull('deleted_at'),
            ],
            'phone' => [
                'prohibited_unless:identityType,phone',
                'required_unless:email,null',
                'string',
                'regex:/^(?:\+8801|01)\d{9}$/',
                Rule::exists('users', 'phone')->where('verified', 0)->whereNull('deleted_at'),
            ],
            'otp' => 'length:4',
        ];
    }
}
