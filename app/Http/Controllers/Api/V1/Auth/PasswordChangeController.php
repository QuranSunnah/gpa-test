<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordChangeRequest;
use App\Models\User;
use App\Services\PasswordChangeService;
use Illuminate\Http\Response as Res;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    public function __construct(private PasswordChangeService $service) {}

    public function changePassword(PasswordChangeRequest $request)
    {
        $this->service->changePassword($request);

        return response()->json([
            'message' => 'Password changed successfully',
        ], Res::HTTP_OK);
    }
}
