<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Services\Interfaces\LoginServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class LoginService implements LoginServiceInterface
{
    public function login(LoginRequest $request): array
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw new UnauthorizedException('Invalid login credentials');
        }

        $user = Auth::user();

        return [
            ...$user->toArray(),
            'token' => $user->createToken('api_auth_token')->accessToken,
        ];
    }
}
