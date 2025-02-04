<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentUpdateRequest;
use App\Http\Requests\UpdateProfileImageRequest;
use App\Models\User;
use App\Services\StudentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    use ApiResponse;

    public function __construct(private StudentService $service)
    {
    }

    public function getProfileInfo(Request $request): JsonResponse
    {
        $userInfo = User::select([
            'id',
            'email',
            'phone',
            'gender',
            'images',
            'dob',
            'blood_group',
            'address',
            'social_links',
            'institute_name',
            'institute_id',
            'academic_status',
            'first_name',
            'last_name',
            'designation',
        ])
            ->where('id', Auth::id())
            ->where('status', config('common.status.active'))
            ->firstOrFail();

        return $this->response($userInfo, __('Student profile info'));
    }

    public function update(StudentUpdateRequest $request): JsonResponse
    {
        $this->service->update($request);

        return $this->response([], __('Student profile updated successfully'));
    }

    public function changePhoto(UpdateProfileImageRequest $request): JsonResponse
    {
        $uplodedImage = $this->service->uploadProfileImage($request);

        return $this->response($uplodedImage, __('Student photo updated successfully'));
    }
}
