<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => [
                'required',
                'regex:/^(\+8801[1-9][0-9]{8}|8801[1-9][0-9]{8}|01[1-9][0-9]{8})$/',
            ],
            'message' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number format is invalid.',
        ];
    }
}
