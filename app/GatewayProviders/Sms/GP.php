<?php

declare(strict_types=1);

namespace App\GatewayProviders\Sms;

use App\GatewayProviders\Interfaces\Sendable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GP implements Sendable
{
    private const GATEWAY_URL = '';
    private const API_TOKEN = '';
    private const SID = '';

    public function send(string $phoneNumber, string $msg): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])->post(self::GATEWAY_URL, [
            'api_token' => self::API_TOKEN,
            'sid' => self::SID,
            'msisdn' => $phoneNumber,
            'sms' => $msg,
            'csms_id' => Str::random(20),
        ]);

        $response = $response->json();
        if ($response['status'] !== 'SUCCESS') {
            Log::error('SMS send failed', [
                'number' => $phoneNumber,
                'message' => $response['error_message'],
            ]);
        }

        return $response;
    }
}
