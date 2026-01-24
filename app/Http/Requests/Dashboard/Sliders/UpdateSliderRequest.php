<?php

namespace App\Http\Requests\Dashboard\Sliders;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8048',
        ];
    }
}
