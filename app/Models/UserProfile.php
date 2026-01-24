<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'name',
        'phone',
        'photo',
        'country',
        'city',
        'whats_app_number',
        'store_number',
        'store_establish_date',
        'tax_number',
        'commercial_registration',
        'linked_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
