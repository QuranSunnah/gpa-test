<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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
            'emial' => [
                'email:rfc,dns',
                'max:255',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $value)
                        ->active()
                        ->first();

                    if (!$user) {
                        return $fail('User not exists with this email');
                    }
                },
            ],
            'otp' => [
                'required',
                'digits:4',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $this->email)
                        ->active()
                        ->first();

                    if ($user) {
                        if ($user->last_otp != $value) {
                            return $fail('Invalid OTP provided.');
                        }
                        if (
                            Carbon::parse($user->otp_created_at)->lt(Carbon::now()
                                ->subMinutes(config('common.otp_expired_duration_at_min')))
                        ) {
                            return $fail('OTP has expired. Please resend OTP');
                        }
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }
}
