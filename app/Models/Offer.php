<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'price',
        'details',
        'latitude',
        'longitude',
        'start_date',
        'expiration_date',
        'phone',
        'is_active',
        'location_name',
        'location_details',
        'price_before',
        'price_after',
        'views',
        'likes',
        'address_ar',
        'city_ar',
        'governorate_ar',
        'country_ar',
        'address_en',
        'city_en',
        'governorate_en',
        'country_en',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(OfferImage::class);
    }

    public function socialMedia()
    {
        return $this->hasMany(OfferSocialMedia::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_offers', 'offer_id', 'user_id')->withTimestamps();
    }
}
