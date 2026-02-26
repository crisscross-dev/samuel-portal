<?php

namespace App\Http\Requests\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('faculty');
    }

    public function rules(): array
    {
        return [
            'grades'               => ['required', 'array'],
            'grades.*.final_grade' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'grades.*.final_grade.min' => 'Grade must be between 0 and 100.',
            'grades.*.final_grade.max' => 'Grade must be between 0 and 100.',
        ];
    }
}
