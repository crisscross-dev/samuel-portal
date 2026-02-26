<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $faculty = $this->route('faculty');

        return [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email,' . $faculty->user_id],
            'password'       => ['nullable', 'string', 'min:8'],
            'employee_id'    => ['required', 'string', 'max:50', 'unique:faculty,employee_id,' . $faculty->id],
            'department_id'  => ['required', 'exists:departments,id'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'is_active'      => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'This email address is already registered by another user.',
            'employee_id.unique' => 'This Employee ID is already taken by another faculty.',
        ];
    }
}
