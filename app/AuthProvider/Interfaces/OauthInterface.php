<?php

declare(strict_types=1);

namespace App\AuthProvider\Interfaces;

interface OauthInterface
{
    public function fetchOauthInfo(string $token, string $platform): array;
}
