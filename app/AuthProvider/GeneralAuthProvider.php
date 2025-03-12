<?php

declare(strict_types=1);

namespace App\AuthProvider;

use App\AuthProvider\Interfaces\Authenticable;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class GeneralAuthProvider implements Authenticable
{
    public function authenticate(LoginRequest $request): User
    {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user->password === sha1($request->password)) {
                Auth::login($user);
                $user->update(['password' => Hash::make($request->password)]);

                return $this->validateUser($user);
            }

            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);

                return $this->validateUser($user);
            }
        } catch (\Exception $e) {
            throw new UnauthorizedException('Invalid login credentials');
        }

        throw new UnauthorizedException('Invalid login credentials');
    }

    private function validateUser(User $user): User
    {
        if ($user->is_verified !== config('common.confirmation.yes')) {
            throw new UnauthorizedException('Your account is not verified. Please reset password.');
        }

        if ($user->status !== config('common.user_status.active')) {
            throw new UnauthorizedException('Your account is currently inactive.');
        }

        $validVerificationMethods = [
            config('common.verified_by.email'),
            config('common.verified_by.phone'),
        ];

        if (!in_array($user->verified_by, $validVerificationMethods, true)) {
            throw new UnauthorizedException('Your account verification method is not recognized.');
        }

        return $user;
    }
}
