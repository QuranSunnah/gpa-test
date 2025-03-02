<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Factories\AuthFactory;
use App\Http\Requests\LoginRequest;

class AuthenticateService
{
    public function authenticateUser(LoginRequest $request): array
    {
        $instance = AuthFactory::create($request->post('provider') ?? 'general');
        $user = $instance->authenticate($request);

        $user->last_login = now();
        $user->save();

        return [
            ...$user->toArray(),
            'token' => $user->createToken('api_auth_token')->accessToken,
        ];
    }
}
