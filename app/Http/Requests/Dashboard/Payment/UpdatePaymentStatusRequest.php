<?php

namespace App\Http\Requests\Dashboard\Payment;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'     => 'required|in:pending,paid,failed',
            'payment_id' => 'nullable|string|max:255',
        ];
    }
}
