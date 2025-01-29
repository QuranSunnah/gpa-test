<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:548',
        ];
    }

    public function messages(): array
    {
        return [
            'profile_image.required' => 'Profile image is required.',
            'profile_image.image' => 'The file must be an image.',
            'profile_image.mimes' => 'Only JPEG, PNG, JPG, and GIF files are allowed.',
            'profile_image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}
