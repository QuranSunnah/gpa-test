<?php

declare(strict_types=1);

namespace App\AuthProvider;

use App\AuthProvider\Interfaces\Authenticable;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class GeneralAuthProvider implements Authenticable
{
    public function authenticate(LoginRequest $request): User
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if (
                $user->is_verified == config('common.confirmation.yes')
                && $user->status == config('common.user_status.active')
                && ($user->verified_by == config('common.verified_by.email')
                    || $user->verified_by == config('common.verified_by.phone')
                )
            ) {
                return $user;
            }
            throw new UnauthorizedException('Invalid login credentials');
        }
        throw new UnauthorizedException('Invalid login credentials');
    }
}
