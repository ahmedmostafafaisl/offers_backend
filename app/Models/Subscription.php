<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'expiration_date',
        'type',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'expiration_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payment()
    {
        return $this->hasOne(\App\Models\Payment::class);
    }
}
