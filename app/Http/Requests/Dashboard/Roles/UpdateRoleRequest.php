<?php

namespace App\Http\Requests\Dashboard\Roles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('role');
        return [
            'name' => 'required|string|max:100|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ];
    }
}
