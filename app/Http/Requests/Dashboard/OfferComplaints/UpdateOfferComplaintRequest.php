<?php

namespace App\Http\Requests\Dashboard\OfferComplaints;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfferComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'offer_id' => 'required|exists:offers,id',
            'subject' => 'nullable|string|max:255',
            'description' => 'required|string',
        ];
    }
}
