<?php

declare(strict_types=1);

namespace App\GatewayProviders\Interfaces;

interface Sendable
{
    public function send(string $phoneNumber, string $otp): array;
}
