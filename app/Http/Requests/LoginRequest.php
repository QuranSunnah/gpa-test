<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        switch ($this->post('provider') ?? 'general') {
            case 'google':
                return $this->validateOauth();
            default:
                return $this->validateGeneralAuth();
        }
    }

    private function validateGeneralAuth()
    {
        return [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:6|max:60',
        ];
    }

    private function validateOauth()
    {
        return [
            'provider' => 'required|string|in:google',
            'platform' => 'string|in:web,ios,android|nullable',
        ];
    }
}
