<?php

namespace App\Http\Requests;


use App\Repositories\EnrollRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\UnauthorizedException;

class LessonProgressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    protected $enrollmentRepository;

    public function __construct(EnrollRepository $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    public function authorize(): bool
    {
        if (!$this->enrollmentRepository->isStudentEnrolled($this->route('slug'))) {
            throw new UnauthorizedException('Student not enrolled in this course.');
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('patch')) {
            return [
                'lesson_id' => 'required|exists:lessons,id',
                'quizzes' => 'nullable|array',
            ];
        }

        return [];
    }
}
