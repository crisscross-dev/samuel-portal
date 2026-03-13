<?php

namespace App\Http\Requests\Registrar;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin', 'cashier']);
    }

    public function rules(): array
    {
        return [
            'enrollment_id'    => ['required', 'exists:enrollments,id'],
            'amount'           => ['required', 'numeric', 'min:0.01'],
            'payment_date'     => ['required', 'date'],
            'payment_method'   => ['required', 'in:cash,bank_transfer,online,check'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'remarks'          => ['nullable', 'string', 'max:500'],
        ];
    }
}
