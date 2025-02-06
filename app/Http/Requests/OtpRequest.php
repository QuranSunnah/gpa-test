<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class OtpRequest extends FormRequest
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
            // 'password' => 'required|min:6|max:60',
            'email' => [
                'required',
                'email:rfc,dns',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $value)
                        // ->where('is_verified', config('common.confirmation.no'))
                        ->active()
                        ->first();

                    if (!$user) {
                        return $fail('User not exists with this email');
                    }
                    $diffInSec = Carbon::parse($user->otp_created_at)->diffInSeconds(now());

                    if ($diffInSec < env('OTP_RESEND_INTERVAL_SECONDS', 120)) {
                        return $fail('Invalid input: Please try again in a few minutes.');
                    }
                },
            ],
        ];
    }
}
