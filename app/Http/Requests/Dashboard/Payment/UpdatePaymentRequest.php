<?php

namespace App\Http\Requests\Dashboard\Payment;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_type' => 'sometimes|string|max:50',
            'phone'        => 'nullable|string|max:30',
            'status'       => 'sometimes|in:pending,paid,failed',
            'payment_id'   => 'nullable|string|max:255',
            'amount'       => 'nullable|numeric|min:0',
            'user_id'      => 'nullable|exists:users,id',

            // لو بعت items → نستبدل
            'items'                 => 'nullable|array|min:1',
            'items.*.name'          => 'required_with:items|string|max:255',
            'items.*.item_number'   => 'nullable|string|max:100',
            'items.*.price'         => 'required_with:items|numeric|min:0',
            'items.*.quantity'      => 'required_with:items|integer|min:1',
        ];
    }
}
