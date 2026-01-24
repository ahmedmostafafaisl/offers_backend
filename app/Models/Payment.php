<?php

namespace App\Models;

use App\Models\PaymentItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'payment_type',
        'amount',
        'payment_id',
        'status',
        'phone',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];


    protected static function booted()
    {
        static::creating(function (Payment $payment) {

            do {
                // مثال: PAY-20260123-8F3K9Q2A
                $reference = 'PAY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8));
            } while (self::where('reference_id', $reference)->exists());

            $payment->reference_id = $reference;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PaymentItem::class);
    }
    public function subscription()
    {
        return $this->belongsTo(\App\Models\Subscription::class);
    }
}
