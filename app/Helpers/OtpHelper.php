<?php

declare(strict_types=1);

namespace App\Helpers;

use App\GatewayProviders\Interfaces\Sendable;
use App\Mails\OtpMail;
use App\Services\MailerService;

class OtpHelper
{
    public static function generateOtp(): string
    {
        return collect(range(1, 4))
            ->map(fn () => collect(str_split('0123456789'))->random())
            ->implode('');
    }

    public static function sendMailOtp(string $email, string $otp)
    {
        MailerService::send($email, new OtpMail(['otp' => $otp]));
    }

    public static function sendSmsOtp(Sendable $smsGateway, string $phoneNumber, string $otp)
    {
        return $smsGateway->send($phoneNumber, $otp);
    }
}
