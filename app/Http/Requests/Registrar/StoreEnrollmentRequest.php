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
            'student_id'    => ['nullable', 'required_without:application_id', 'exists:students,id'],
            'application_id' => ['nullable', 'required_without:student_id', 'exists:applications,id'],
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
