<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    /** Billing type => price column. */
    public const TYPES = [
        'quarterly'   => 'quarterly_price',
        'semi_annual' => 'semi_annual_price',
        'annual'      => 'annual_price',
    ];

    protected $fillable = [
        'name',
        'quarterly_price',
        'semi_annual_price',
        'annual_price',
    ];

    public function features()
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /** Price for a billing type, or null when the plan does not offer that type. */
    public function priceFor(string $type): ?float
    {
        $column = self::TYPES[$type] ?? null;

        if (!$column || $this->{$column} === null) {
            return null;
        }

        return (float) $this->{$column};
    }

    public function offersType(string $type): bool
    {
        return $this->priceFor($type) !== null;
    }

    /** @return array<int, array{type: string, price: float}> */
    public function availableTypes(): array
    {
        $out = [];
        foreach (array_keys(self::TYPES) as $type) {
            if ($this->offersType($type)) {
                $out[] = ['type' => $type, 'price' => $this->priceFor($type)];
            }
        }

        return $out;
    }
}
