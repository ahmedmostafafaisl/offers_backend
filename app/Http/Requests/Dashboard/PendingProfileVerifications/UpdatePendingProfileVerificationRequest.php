<?php

namespace App\Http\Requests\Dashboard\PendingProfileVerifications;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePendingProfileVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'requester_user_id' => 'required|exists:users,id',
            'target_user_id' => 'nullable|exists:users,id',
            'type' => 'required|in:employee,provider,customer',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'otp_hash' => 'nullable|string|max:255',
            'expires_at' => 'nullable|date',
            'verified_at' => 'nullable|date',
        ];
    }
}
