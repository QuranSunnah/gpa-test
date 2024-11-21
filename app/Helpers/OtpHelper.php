<?php

declare(strict_types=1);

namespace App\Helpers;

use App\GatewayProviders\Interfaces\Sendable;
use App\GatewayProviders\Sms\GP;
use App\Mails\OtpMail;
use App\Services\Mailer\MailerService;

class OtpHelper
{
    public static function generateOtp(): string
    {
        return collect(range(1, 4))
            ->map(fn () => collect(str_split('0123456789'))->random())
            ->implode('');
    }

    // public static function sendOtp(string $identity, string $otp)
    // {
    //     if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
    //         self::sendMailOtp($identity, $otp);
    //     } else {
    //         self::sendSmsOtp(new GP(), $identity, $otp);
    //     }
    // }

    public static function sendMailOtp(string $email, string $otp)
    {
        MailerService::send($email, new OtpMail([
            'email' => $email,
            'email_otp' => $otp,
        ]));
    }

    public static function sendSmsOtp(Sendable $smsGateway, string $phoneNumber, string $otp)
    {
        $msg = "{$otp} is your GP Academy OTP";

        return $smsGateway->send($phoneNumber, $msg);
    }
}
