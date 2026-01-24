<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'offer_id',
        'title_ar',
        'title_en',
        'body_ar',
        'body_en',
        'data',
        'sent_at',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
