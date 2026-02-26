<?php

namespace App\Http\Requests\Registrar;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'registrar']);
    }

    public function rules(): array
    {
        return [
            'student_id'    => ['required', 'exists:students,id'],
            'semester_id'   => ['required', 'exists:semesters,id'],
            'section_id'    => ['required', 'exists:sections,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'section_id.required' => 'Please select a section for the student.',
        ];
    }
}
