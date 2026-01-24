<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OffersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('offers')->truncate();

        $now = now();
        $providers = DB::table('users')->where('type', 'provider')->orderBy('id')->get();
        $categories = DB::table('categories')->orderBy('id')->get();

        if ($providers->isEmpty() || $categories->isEmpty()) return;

        $rows = [];
        $count = 30;

        for ($i = 1; $i <= $count; $i++) {
            $provider = $providers[($i - 1) % $providers->count()];
            $cat = $categories[($i - 1) % $categories->count()];

            $before = 200 + $i;
            $after  = max(50, $before - (10 * (($i % 5) + 1)));

            $rows[] = [
                'user_id' => $provider->id,
                'category_id' => $cat->id,

                'name' => "Offer #{$i} - {$provider->city}",
                'price' => $after,
                'details' => "Special offer in {$provider->city} (Saudi Arabia)",

                'latitude' => $provider->latitude,
                'longitude' => $provider->longitude,

                'start_date' => now()->toDateString(),
                'expiration_date' => now()->addDays(30)->toDateString(),

                'phone' => $provider->phone,
                'is_active' => true,

                'location_name' => $provider->city_en ?? $provider->city,
                'location_details' => $provider->address_en ?? 'KSA',

                'price_before' => $before,
                'price_after' => $after,

                'views' => 0,
                'likes' => 0,

                // Saudi bilingual address
                'address_ar' => $provider->address_ar,
                'city_ar' => $provider->city_ar,
                'governorate_ar' => $provider->governorate_ar,
                'country_ar' => $provider->country_ar,

                'address_en' => $provider->address_en,
                'city_en' => $provider->city_en,
                'governorate_en' => $provider->governorate_en,
                'country_en' => $provider->country_en,

                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('offers')->insert($rows);
    }
}
