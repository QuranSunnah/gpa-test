<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
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
        $yes = config('common.confirmation.yes');
        $active = config('common.user_status.active');

        return [
            'identity_type' => 'required|string|in:email,phone',
            'identity' => [
                'required',
                Rule::when(
                    $this->post('identity_type') === 'email',
                    [
                        'email:rfc,dns',
                        'max:255',
                        function ($attribute, $value, $fail) use ($yes, $active) {
                            $user = User::where('email', $value)->first();
                            if ($user) {
                                if ($user->is_verified == $yes) {
                                    return $fail('This email is already registered, please login');
                                } elseif ($user->status != $active) {
                                    return $fail('You are inactive user, please contact with system admin');
                                }
                            } else {
                                return $fail('This email is not exists.');
                            }
                        },
                    ]
                ),

                Rule::when(
                    $this->post('identity_type') === 'phone',
                    [
                        'string',
                        'regex:/^(?:\+8801|01)\d{9}$/',
                        function ($attribute, $value, $fail) use ($yes, $active) {
                            $user = User::where('phone', $value)->first();
                            if ($user) {
                                if ($user->is_verified == $yes) {
                                    return $fail('This phone number is already registered, please login');
                                } elseif ($user->status != $active) {
                                    return $fail('You are inactive user, please contact with system admin');
                                }
                            } else {
                                return $fail('This phone is not exists.');
                            }
                        },
                    ]
                ),
            ],
            'otp' => [
                'required',
                'digits:4',
                function ($attribute, $value, $fail) {
                    $user = User::where($this->identity_type, $this->identity)
                        ->where('is_verified', 0)
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
        ];
    }
}
