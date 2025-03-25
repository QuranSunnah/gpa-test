<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Auth;

class ContactUsService
{
    public function save(ContactUsRequest $request): void
    {
        $user = Auth::guard('api')->user();

        ContactUs::create([
            'gp_id' => $user?->gp_id ?? null,
            'full_name' => $user?->full_name ?? $request->full_name,
            'email' => $user?->email ?? $request->email,
            'phone' => $user?->phone ?? $request->phone,
            'message' => $request->message,
        ]);
    }
}
