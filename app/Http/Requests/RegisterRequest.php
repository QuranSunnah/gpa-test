<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            $no = config('common.confirmation.no');
            $active = config('common.user_status.active');

            return [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email:rfc,dns',
                    function ($attribute, $value, $fail) use ($no, $active) {
                        $user = User::where('email', $value)->first();
                        if ($user) {
                            if ($user->is_verified == $no) {
                                return $fail(
                                    'This email is already registered. Please verify OTP to complete registration.'
                                );
                            } elseif ($user->status != $active) {
                                return $fail('You are inactive user, please contact with system admin');
                            }

                            return $fail('This email is already registered');
                        }
                    },
                ],
                'phone' => [
                    'required',
                    'string',
                    'min:11',
                    'max:50',
                    function ($attribute, $value, $fail) use ($no, $active) {
                        $user = User::where('phone', $value)->first();
                        if ($user) {
                            if ($user->is_verified == $no) {
                                return $fail(
                                    'This is registered phone number. Please verify OTP to complete registration'
                                );
                            } elseif ($user->status != $active) {
                                return $fail('You are inactive user, please contact with system admin');
                            }

                            return $fail('This phone number is already registered');
                        }
                    },
                ],
                'password' => 'required|confirmed|min:6|max:60',
                'gender' => 'required|integer|min:1|max:3',
                'designation' => 'required|integer',
            ];
        }
    }
}
