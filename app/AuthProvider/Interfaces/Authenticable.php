<?php

declare(strict_types=1);

namespace App\AuthProvider\Interfaces;

use App\Http\Requests\LoginRequest;
use App\Models\User;

interface Authenticable
{
    public function authenticate(LoginRequest $request): User;
}
