<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferComplaint extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'offer_id', 'subject', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
