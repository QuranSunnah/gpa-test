<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\RegistrationCompleted;
use App\Factories\RegisterFactory;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegistrationCompleteRequest;
use App\Models\User;
use App\Services\Interfaces\RegisterServiceInterface;

class RegisterService implements RegisterServiceInterface
{
    public function register(RegisterRequest $request): array
    {
        $instance = RegisterFactory::create($request->post('provider'));

        return $instance->execute($request);
    }

    public function complete(RegistrationCompleteRequest $request): array
    {
        $identityType = $request->post('identity_type');
        $user = User::where($identityType, $request->post('identity'))->first();
        $user->is_verified = config('common.confirmation.yes');
        $user->verified_by = config("common.verified_by.{$identityType}");
        $user->save();

        event(new RegistrationCompleted($user));

        return [
            $identityType => $request->post('identity'),
            'token' => $user->createToken('api_auth_token')->accessToken,
        ];
    }
}
