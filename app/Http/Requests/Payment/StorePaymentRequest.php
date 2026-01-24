<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_type' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',

            // amount ممكن نخليه nullable لأنه هيتحسب من items
            'amount' => 'nullable|numeric|min:0',

            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.item_number' => 'nullable|string|max:100',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }
}
