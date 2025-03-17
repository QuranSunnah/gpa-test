<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\RegistrationCompleted;
use App\Events\RegistrationProcessed;
use App\Helpers\OtpHelper;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegistrationCompleteRequest;
use App\Models\Institute;
use App\Models\User;
use Carbon\Carbon;

class RegisterService
{
    public function register(RegisterRequest $request): array
    {
        $user = new User();
        $otp = OtpHelper::generateOtp();

        [$firstName, $lastName] = $this->splitFullName($request->full_name);

        $user->fill($request->only(
            'email',
            'password',
            'phone',
            'gender',
            'designation',
            'institute_id',
        ));
        if ($request->designation == config('common.designation.student')) {
            $user->institute_name = Institute::find($request->post('institute_id'))->name;
        }
        $user->first_name = $firstName;
        $user->last_name = $lastName;

        $user->last_otp = $otp;
        $user->otp_created_at = Carbon::now();
        $user->save();

        event(new RegistrationProcessed($request->post('email'), $request->post('phone'), $otp));

        return [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'designation' => $user->designation,
        ];
    }

    public function complete(RegistrationCompleteRequest $request): array
    {
        $identityType = $request->post('identity_type');
        $user = User::where($identityType, $request->post('identity'))->first();
        $user->is_verified = config('common.confirmation.yes');
        $user->verified_by = config("common.verified_by.{$identityType}");
        $user->save();

        event(new RegistrationCompleted($user));

        return [
            $identityType => $request->post('identity'),
            'token' => $user->createToken('api_auth_token')->accessToken,
        ];
    }

    private function splitFullName(?string $fullName): array
    {
        $fullName = trim($fullName);

        $parts = explode(' ', $fullName);
        $count = count($parts);

        if ($count === 1) {
            return [$parts[0], null];
        }

        return [
            implode(' ', array_slice($parts, 0, $count - 1)),
            $parts[$count - 1],
        ];
    }
}
