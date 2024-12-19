<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\RegistrationProcessed;
use App\Helpers\OtpHelper;
use App\Http\Requests\OtpRequest;
use App\Models\User;
use Carbon\Carbon;

class OtpService
{
    public function send(OtpRequest $request): void
    {
        $otp = OtpHelper::generateOtp();
        $user = User::where('email', $request->post('email'))->first();
        $user->last_otp = $otp;
        $user->otp_created_at = Carbon::now();
        $user->save();
        event(new RegistrationProcessed($request->post('email'), $user->phone, $otp));
    }
}
