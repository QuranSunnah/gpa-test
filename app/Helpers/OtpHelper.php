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

    public static function sendOtp(string $email, string $phone, string $otp)
    {
        self::sendMailOtp($email, $otp);
        self::sendSmsOtp(new GP(), $phone, $otp);
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
