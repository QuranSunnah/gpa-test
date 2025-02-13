<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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
            'email' => [
                'required',
                'email:rfc,dns',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $value)
                        ->active()
                        ->first();

                    if (!$user) {
                        return $fail('User not exists with this email');
                    }
                    $diffInSec = Carbon::parse($user->otp_created_at)->diffInSeconds(now());

                    if ($diffInSec < (int) config('common.otp_resend_interval_seconds')) {
                        return $fail('Invalid input: Please try again in a few minutes.');
                    }
                },
            ],
        ];
    }
}
