<?php

declare(strict_types=1);

namespace App\AuthProvider;

use App\AuthProvider\Interfaces\Authenticable;
use App\AuthProvider\Interfaces\OauthInterface;
use App\Events\RegistrationCompleted;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Google\Client as GoogleClient;
use Illuminate\Http\Response as Res;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class GoogleAuthProvider implements Authenticable, OauthInterface
{
    public function authenticate(LoginRequest $request): User
    {
        if (empty($request->header('Authorization'))) {
            throw new UnauthorizedException('Invalid authorization', Res::HTTP_UNAUTHORIZED);
        }
        $oauthInfo = $this->fetchOauthInfo(
            explode(' ', $request->header('Authorization'))[1],
            $request->post('platform') ?? 'web'
        );
        if (empty($oauthInfo['email'])) {
            throw new UnauthorizedException("We can't access your email address.", Res::HTTP_UNAUTHORIZED);
        }
        $user = User::where('email', $oauthInfo['email'])->first();
        if (empty($user)) {
            $user = User::create([
                'first_name' => $oauthInfo['given_name'] ?? '',
                'last_name' => $oauthInfo['family_name'] ?? '',
                'email' => $oauthInfo['email'],
                'is_verified' => config('common.confirmation.yes'),
                'verified_by' => config('common.verified_by.google'),
            ]);
            event(new RegistrationCompleted($user));
        }
        Auth::login($user);

        return $user;
    }

    public function fetchOauthInfo(string $token, string $platform): array
    {
        $client = new GoogleClient(['client_id' => config('auth.google_client_id.' . $platform)]);
        $oauthInfo = $client->verifyIdToken($token);
        if (empty($oauthInfo)) {
            throw new UnauthorizedException('Google unauthorized request.', Res::HTTP_UNAUTHORIZED);
        }

        return $oauthInfo;
    }
}
