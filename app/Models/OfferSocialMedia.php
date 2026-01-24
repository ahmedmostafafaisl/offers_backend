<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferSocialMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'platform',
        'url',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
