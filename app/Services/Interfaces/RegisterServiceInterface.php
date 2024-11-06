<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Http\Requests\RegisterRequest;

interface RegisterServiceInterface
{
    public function register(RegisterRequest $request): array;
}
