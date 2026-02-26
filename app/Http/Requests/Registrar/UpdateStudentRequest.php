<?php

namespace App\Http\Requests\Registrar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'registrar']);
    }

    public function rules(): array
    {
        $studentId = $this->route('student');

        return [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $studentId?->user_id],
            'student_id'        => ['required', 'string', 'max:50', 'unique:students,student_id,' . $studentId?->id],
            'program_id'        => ['required', 'exists:programs,id'],
            'year_level'        => ['required', 'integer', 'min:1', 'max:6'],
            'status'            => ['required', 'in:applicant,active,inactive,graduated,dropped'],
            'date_of_birth'     => ['nullable', 'date', 'before:today'],
            'gender'            => ['nullable', 'in:male,female,other'],
            'address'           => ['nullable', 'string', 'max:500'],
            'contact_number'    => ['nullable', 'string', 'max:20'],
            'guardian_name'     => ['nullable', 'string', 'max:255'],
            'guardian_contact'  => ['nullable', 'string', 'max:20'],
        ];
    }
}
