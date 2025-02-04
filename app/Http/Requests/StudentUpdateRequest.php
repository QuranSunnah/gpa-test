<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'gender' => ['nullable', 'integer', Rule::in(array_values(config('common.gender')))],
            'dob' => ['nullable', 'date'],
            'blood_group' => ['nullable', 'integer', Rule::in(array_values(config('common.blood_group')))],
            'address' => ['nullable', 'string', 'max:255'],
            'social_links' => ['nullable', 'url'],
            'institute_id' => ['required', 'integer', 'exists:institutes,id'],
            'academic_status' => ['nullable', 'integer', Rule::in(array_values(config('common.academic_status')))],
            'designation' => ['required', 'string', Rule::in(array_values(config('common.designation')))]
        ];
    }

    /**
     * Get the custom validation messages for the defined rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'gender.in' => 'The selected gender is invalid.',
            'dob.date' => 'The date of birth must be a valid date.',
            'blood_group.in' => 'The selected blood group is invalid.',
            'address.max' => 'The address must not exceed 255 characters.',
            'social_links.url' => 'The social links must be a valid URL.',
            'social_links.max' => 'The social links must not exceed 255 characters.',
            'institute_id.required' => 'The institute ID is required.',
            'institute_id.integer' => 'The institute ID must be an integer.',
            'institute_id.exists' => 'The selected institute ID is invalid.',
            'academic_status.in' => 'The selected academic status is invalid.',
        ];
    }
}
