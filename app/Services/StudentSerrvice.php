<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\StudentUpdateRequest;
use App\Http\Requests\UpdateProfileImageRequest;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentSerrvice
{
    public function update(StudentUpdateRequest $request): void
    {
        $validatedData = $request->validated();
        $student = User::findOrfail(Auth::id());

        if (isset($validatedData['institute_id'])) {
            $validatedData['institute_name'] = Institute::findOrFail($validatedData['institute_id'])->name;
        }
        if (isset($validatedData['social_links'])) {
            $validatedData['social_links'] = json_encode([
                'linkedin' => filter_var($validatedData['social_links'], FILTER_SANITIZE_URL),
            ]);
        }
        $student->update($validatedData);
    }

    public function uploadProfileImage(UpdateProfileImageRequest $request): array
    {
        $user = User::findOrfail(Auth::id());
        $storageDisk = env('FILESYSTEM_DISK', 'public');
        $extension = $request->file('profile_image')->getClientOriginalExtension();
        $hashedFilename = md5((string) $user->id) . '.' . $extension;

        if ($user->images && isset($user->images['profile'])) {
            if (Storage::exists($user->images['profile'])) {
                Storage::delete($user->images['profile']);
            }
        }

        $filePath = "profile_images/{$hashedFilename}";
        $request->file('profile_image')->storeAs('profile_images', $hashedFilename, $storageDisk);

        $imageData = [
            ...$user->images,
            'profile' => $filePath,
        ];
        $user->images = json_encode($imageData);
        $user->save();

        return $imageData;
    }
}
