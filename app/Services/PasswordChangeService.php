<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\PasswordChangeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChangeService
{
    public function changePassword(PasswordChangeRequest $request): void
    {
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
    }
}
