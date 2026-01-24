<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class OfferComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offer_id' => 'required|exists:offers,id',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
