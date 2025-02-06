<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class PasswordResetService
{
    public function reset(Request $request): void
    {
        $user = User::where('email', $request->post('email'))->first();
        $user->fill($request->only(
            'password',
        ));
        $user->save();
    }
}
