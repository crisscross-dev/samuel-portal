<?php

namespace App\Http\Requests\Registrar;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'registrar']);
    }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'          => ['required', 'confirmed', Password::defaults()],
            'student_id'        => ['required', 'string', 'max:50', 'unique:students,student_id'],
            'program_id'        => ['required', 'exists:programs,id'],
            'year_level'        => ['required', 'integer', 'min:1', 'max:6'],
            'date_of_birth'     => ['nullable', 'date', 'before:today'],
            'gender'            => ['nullable', 'in:male,female,other'],
            'address'           => ['nullable', 'string', 'max:500'],
            'contact_number'    => ['nullable', 'string', 'max:20'],
            'guardian_name'     => ['nullable', 'string', 'max:255'],
            'guardian_contact'  => ['nullable', 'string', 'max:20'],
        ];
    }
}
