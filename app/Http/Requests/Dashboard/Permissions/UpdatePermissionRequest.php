<?php

namespace App\Http\Requests\Dashboard\Permissions;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('permission');
        return [
            'name' => 'required|string|max:120|unique:permissions,name,' . $id,
        ];
    }
}
