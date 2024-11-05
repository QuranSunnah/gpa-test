<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\Interfaces\RegisterServiceInterface;
use Illuminate\Auth\Events\Registered;

class RegisterService implements RegisterServiceInterface
{
    public function register(RegisterRequest $request): array
    {
        $user = new User();
        $user->fill($request->only(
            'first_name',
            'last_name',
            'email',
            'password',
            'phone',
            'gender',
            'designation',
        ))->save();

        event(new Registered($user));

        return [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'designation' => $user->designation,
            'token' => $user->createToken('api_auth_token')->accessToken,
        ];
    }
}
