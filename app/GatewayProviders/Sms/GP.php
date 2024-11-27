<?php

declare(strict_types=1);

namespace App\GatewayProviders\Sms;

use App\GatewayProviders\Interfaces\Sendable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GP implements Sendable
{
    public function send(string $phoneNumber, string $otp): array
    {
        $apiResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAuthToken(),
        ])->post(env('SMS_TOKEN_URL'), [
            'transactionId' => time() . rand(1000, 9999),
            'subject' => env('SMS_API_SUBJECT'),
            'type' => env('SMS_API_TYPE'),
            'contentEn' => "Your GP Academy account verification code is: $otp",
            'contentBn' => "আপনার জিপি একাডেমি অ্যাকাউন্ট ভেরিফিকেশনের কোডটি হচ্ছে: $otp",
            'chargeCode' => env('SMS_API_CHARGE_CODE'),
            'receiver' => [
                'appUserId' => '',
                'phoneNumber' => '88' . $phoneNumber,
            ],
            'sender' => [
                'id' => 'GP-Academy',
            ],
        ]);

        $response = $apiResponse->json();
        if ($apiResponse->failed()) {
            Log::error('SMS send failed', [
                'phoneNumber' => $phoneNumber,
                ...$response,
            ]);
        }

        return $response;
    }

    private function getAuthToken(): string
    {
        $tokenInfo = $this->fetchTokenInfo();
        if (isset($tokenInfo['accessToken']) && isset($tokenInfo['expiresIn'])) {
            return Cache::remember('sms_access_token', $tokenInfo['expiresIn'], function () use ($tokenInfo) {
                return $tokenInfo['accessToken'];
            });
        }

        return '';
    }

    private function fetchTokenInfo(): array
    {
        $response = Http::asForm()->post(env('SMS_AUTH_TOKEN_URL'), [
            'client_id' => env('SMS_CLIENT_ID'),
            'client_secret' => env('SMS_CLIENT_SECRET'),
            'grant_type' => env('SMS_CLIENT_GRANT_TYPE'),
        ]);

        return $response->failed() ? [] : $response->json();
    }
}
