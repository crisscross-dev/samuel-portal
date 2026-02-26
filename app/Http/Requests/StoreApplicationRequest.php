<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Public form — no authentication required.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'         => ['required', 'string', 'max:255'],
            'last_name'          => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255', 'unique:applications,email', 'unique:users,email'],
            'contact_number'     => ['nullable', 'string', 'max:20'],
            'date_of_birth'      => ['nullable', 'date', 'before:today'],
            'gender'             => ['nullable', 'in:male,female,other'],
            'address'            => ['nullable', 'string', 'max:1000'],
            'program_applied_id' => ['required', 'exists:programs,id'],
            'year_level'         => ['required', 'integer', 'min:1', 'max:6'],
            'guardian_name'      => ['nullable', 'string', 'max:255'],
            'guardian_contact'   => ['nullable', 'string', 'max:20'],
            'document'           => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'              => 'This email has already been used in an existing application or account.',
            'program_applied_id.exists' => 'The selected program is invalid.',
            'document.max'              => 'The uploaded document must not exceed 5MB.',
        ];
    }
}
