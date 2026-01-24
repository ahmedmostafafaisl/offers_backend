<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class FavoriteOfferStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'offer_id' => 'required|exists:offers,id',

        ];
    }
}
