<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserProfilesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_profiles')->truncate();

        $now = now();

        $providers = DB::table('users')->where('type', 'provider')->orderBy('id')->get();
        $customers = DB::table('users')->where('type', 'customer')->orderBy('id')->get();

        $base = [
            'user_id' => null,
            'linked_user_id' => null,
            'type' => null,

            'name' => null,
            'phone' => null,
            'photo' => null,
            'country' => null,
            'city' => null,

            'whats_app_number' => null,
            'store_number' => null,
            'store_establish_date' => null,
            'tax_number' => null,
            'commercial_registration' => null,

            'created_at' => $now,
            'updated_at' => $now,
        ];

        $profiles = [];

        // Create 10 pairs
        for ($i = 0; $i < 10; $i++) {
            $provider = $providers[$i] ?? null;
            $customer = $customers[$i] ?? null;
            if (!$provider || !$customer) break;

            // provider -> customer
            $profiles[] = array_merge($base, [
                'user_id' => $provider->id,
                'linked_user_id' => $customer->id,
                'type' => 'customer',
                'name' => $customer->name,
                'phone' => $customer->phone,
                'photo' => $customer->photo,
                'country' => $customer->country,
                'city' => $customer->city,
            ]);

            // customer -> provider
            $profiles[] = array_merge($base, [
                'user_id' => $customer->id,
                'linked_user_id' => $provider->id,
                'type' => 'provider',
                'name' => $provider->name,
                'phone' => $provider->phone,
                'photo' => $provider->photo,
                'country' => $provider->country,
                'city' => $provider->city,

                'whats_app_number' => $provider->whats_app_number,
                'store_number' => $provider->store_number,
                'store_establish_date' => $provider->store_establish_date,
                'tax_number' => $provider->tax_number,
                'commercial_registration' => $provider->commercial_registration,
            ]);
        }

        DB::table('user_profiles')->insert($profiles);

        // set active_profile_id = its profile row id
        $inserted = DB::table('user_profiles')->get();
        foreach ($inserted as $p) {
            DB::table('users')->where('id', $p->user_id)->update(['active_profile_id' => $p->id]);
        }
    }
}
