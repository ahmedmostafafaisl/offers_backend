<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSocialMedia extends Model
{
    use HasFactory;
    protected $table = 'user_social_media';

    protected $fillable = [
        'user_id',
        'platform',
        'url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
