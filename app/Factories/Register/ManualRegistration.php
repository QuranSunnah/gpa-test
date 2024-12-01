<?php

declare(strict_types=1);

namespace App\Factories\Register;

use App\Events\RegistrationProcessed;
use App\Factories\Register\Interfaces\RegisterFactoryInterface;
use App\Helpers\OtpHelper;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;

class ManualRegistration implements RegisterFactoryInterface
{
    public function execute(RegisterRequest $request): array
    {
        $user = new User();
        $otp = OtpHelper::generateOtp();
        $user->fill($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'phone',
            'gender',
            'designation',
        ));
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
}
