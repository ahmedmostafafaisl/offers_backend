<?php

namespace App\Http\Requests\Offer;

use Illuminate\Foundation\Http\FormRequest;

class OfferReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offer_id' => 'required|exists:offers,id',
            'reason' => 'required|string|max:500',
        ];
    }
}
