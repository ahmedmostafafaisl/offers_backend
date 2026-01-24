<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserProfile;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'active_profile_id',
        'type',
        'name',
        'email',
        'password',
        'phone',
        'otp',
        'fcm_token',
        'photo',
        'country',
        'city',
        'whats_app_number',
        'store_number',
        'store_establish_date',
        'tax_number',
        'commercial_registration',
        'pin_code',
        'address_ar',
        'city_ar',
        'governorate_ar',
        'country_ar',
        'address_en',
        'city_en',
        'governorate_en',
        'country_en',
        'latitude',
        'longitude',
        'location_name_ar',
        'location_details_ar',
        'location_name_en',
        'location_details_en',
    ];

    protected $hidden = [
        'remember_token',
        'password',
        'otp',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'store_establish_date' => 'date',

    ];


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }


    public function socialMedia()
    {
        return $this->hasMany(UserSocialMedia::class);
    }

    public function favoriteOffers()
    {
        return $this->belongsToMany(Offer::class, 'favorite_offers', 'user_id', 'offer_id')->withTimestamps();
    }

    public function profiles()
    {
        return $this->hasMany(UserProfile::class);
    }

    public function activeProfile()
    {
        return $this->belongsTo(UserProfile::class, 'active_profile_id');
    }

    public function userNotificationsCustom()
    {
        return $this->hasMany(\App\Models\Notification::class); // لو عندك جدول تاني
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
}
