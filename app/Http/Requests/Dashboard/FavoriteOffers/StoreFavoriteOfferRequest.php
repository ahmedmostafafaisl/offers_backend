<?php

namespace App\Http\Requests\Dashboard\FavoriteOffers;

use Illuminate\Foundation\Http\FormRequest;

class StoreFavoriteOfferRequest extends FormRequest
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
        ];
    }
}
