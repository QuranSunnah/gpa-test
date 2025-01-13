<?php

namespace App\Http\Requests;

use App\Repositories\EnrollRepository;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuizShowRequest extends FormRequest
{
    public function __construct(private EnrollRepository $repository) {}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->repository->isStudentEnrolled($this->route('slug'))) {
            throw new NotFoundHttpException('Student not enrolled in this course.');
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
        return [
            'lesson_id' => 'required|exists:lessons,id',
        ];
    }
}
