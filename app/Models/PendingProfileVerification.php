<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingProfileVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_user_id',
        'target_user_id',
        'type',
        'phone',
        'email',
        'otp_hash',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];
}
