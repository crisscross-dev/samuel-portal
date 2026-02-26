<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTuitionStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $isFlat = $this->input('pricing_type') === 'flat';
        $isPerUnit = $this->input('pricing_type') === 'per_unit';

        return [
            'department_id'    => ['required', 'exists:departments,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'pricing_type'     => ['required', 'in:flat,per_unit'],
            'grade_level_id'   => ['nullable', 'exists:grade_levels,id'],
            'program_id'       => ['nullable', 'exists:programs,id'],
            'label'            => ['nullable', 'string', 'max:100'],
            'flat_amount'      => [$isFlat ? 'required' : 'nullable', 'numeric', 'min:0'],
            'lecture_rate'     => [$isPerUnit ? 'required' : 'nullable', 'numeric', 'min:0'],
            'lab_rate'         => [$isPerUnit ? 'required' : 'nullable', 'numeric', 'min:0'],
            'misc_fee'         => ['required', 'numeric', 'min:0'],
            'reg_fee'          => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'flat_amount.required'  => 'Flat amount is required for flat pricing type.',
            'lecture_rate.required' => 'Lecture rate is required for per-unit pricing type.',
            'lab_rate.required'     => 'Lab rate is required for per-unit pricing type.',
        ];
    }
}
