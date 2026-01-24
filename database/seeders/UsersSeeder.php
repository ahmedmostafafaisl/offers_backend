<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\Traits\SeedsImages;

class UsersSeeder extends Seeder
{
    use SeedsImages;

    public function run(): void
    {
        DB::table('users')->truncate();

        $now = now();

        $ksa = $this->ksaPlaces();

        $base = [
            'active_profile_id' => null,

            'name' => null,
            'email' => null,
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'phone' => null,

            'otp' => null,
            'pin_code' => null,
            'fcm_token' => null,

            'photo' => null,

            'country' => 'Saudi Arabia',
            'city' => null,

            'whats_app_number' => null,
            'store_number' => null,
            'store_establish_date' => null,
            'tax_number' => null,
            'commercial_registration' => null,

            'remember_token' => null,

            // address bilingual
            'address_ar' => null,
            'city_ar' => null,
            'governorate_ar' => null,
            'country_ar' => 'السعودية',

            'address_en' => null,
            'city_en' => null,
            'governorate_en' => null,
            'country_en' => 'Saudi Arabia',

            'latitude' => null,
            'longitude' => null,

            'location_name_ar' => null,
            'location_details_ar' => null,
            'location_name_en' => null,
            'location_details_en' => null,

            'created_at' => $now,
            'updated_at' => $now,
        ];

        $rows = [];

        // 10 Providers
        for ($i = 1; $i <= 10; $i++) {
            $p = $ksa[($i - 1) % count($ksa)];

            $rows[] = array_merge($base, [
                'type' => 'provider',
                'name' => "Provider Store {$i}",
                'email' => "provider{$i}@example.com",
                'phone' => "05000010" . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'photo' => $this->putImage("users/provider{$i}.png", "User Provider #{$i}"),

                'city' => $p['city_en'],

                'address_ar' => $p['address_ar'],
                'city_ar' => $p['city_ar'],
                'governorate_ar' => $p['region_ar'],

                'address_en' => $p['address_en'],
                'city_en' => $p['city_en'],
                'governorate_en' => $p['region_en'],

                'latitude' => $p['lat'],
                'longitude' => $p['lng'],

                'location_name_ar' => "فرع {$p['city_ar']}",
                'location_details_ar' => "موقع داخل {$p['city_ar']}",
                'location_name_en' => "{$p['city_en']} Branch",
                'location_details_en' => "Location inside {$p['city_en']}",

                // provider fields
                'whats_app_number' => "05000010" . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'store_number' => "ST-" . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                'store_establish_date' => '2020-01-01',
                'tax_number' => "TAX-" . (1000 + $i),
                'commercial_registration' => "CR-" . (1000 + $i),
            ]);
        }

        // 10 Customers
        for ($i = 1; $i <= 10; $i++) {
            $p = $ksa[($i + 3) % count($ksa)];

            $rows[] = array_merge($base, [
                'type' => 'customer',
                'name' => "Customer {$i}",
                'email' => "customer{$i}@example.com",
                'phone' => "05000020" . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                'photo' => $this->putImage("users/customer{$i}.png", "User Customer #{$i}"),

                'city' => $p['city_en'],

                'address_ar' => $p['address_ar'],
                'city_ar' => $p['city_ar'],
                'governorate_ar' => $p['region_ar'],

                'address_en' => $p['address_en'],
                'city_en' => $p['city_en'],
                'governorate_en' => $p['region_en'],

                'latitude' => $p['lat'],
                'longitude' => $p['lng'],

                'location_name_ar' => "موقع {$p['city_ar']}",
                'location_details_ar' => "داخل {$p['city_ar']}",
                'location_name_en' => "{$p['city_en']} Location",
                'location_details_en' => "Inside {$p['city_en']}",
            ]);
        }

        DB::table('users')->insert($rows);
    }

    private function ksaPlaces(): array
    {
        return [
            ['city_en' => 'Riyadh', 'city_ar' => 'الرياض', 'region_en' => 'Riyadh Region', 'region_ar' => 'منطقة الرياض', 'address_en' => 'Olaya District', 'address_ar' => 'حي العليا', 'lat' => 24.7136, 'lng' => 46.6753],
            ['city_en' => 'Jeddah', 'city_ar' => 'جدة', 'region_en' => 'Makkah Region', 'region_ar' => 'منطقة مكة المكرمة', 'address_en' => 'Al Rawdah', 'address_ar' => 'حي الروضة', 'lat' => 21.4858, 'lng' => 39.1925],
            ['city_en' => 'Dammam', 'city_ar' => 'الدمام', 'region_en' => 'Eastern Province', 'region_ar' => 'المنطقة الشرقية', 'address_en' => 'Al Shati', 'address_ar' => 'حي الشاطئ', 'lat' => 26.4207, 'lng' => 50.0888],
            ['city_en' => 'Makkah', 'city_ar' => 'مكة', 'region_en' => 'Makkah Region', 'region_ar' => 'منطقة مكة المكرمة', 'address_en' => 'Al Aziziyah', 'address_ar' => 'العزيزية', 'lat' => 21.3891, 'lng' => 39.8579],
            ['city_en' => 'Madinah', 'city_ar' => 'المدينة', 'region_en' => 'Madinah Region', 'region_ar' => 'منطقة المدينة', 'address_en' => 'Quba', 'address_ar' => 'قباء', 'lat' => 24.5247, 'lng' => 39.5692],
            ['city_en' => 'Khobar', 'city_ar' => 'الخبر', 'region_en' => 'Eastern Province', 'region_ar' => 'المنطقة الشرقية', 'address_en' => 'Al Ulaya', 'address_ar' => 'العليا', 'lat' => 26.2172, 'lng' => 50.1971],
            ['city_en' => 'Taif', 'city_ar' => 'الطائف', 'region_en' => 'Makkah Region', 'region_ar' => 'منطقة مكة المكرمة', 'address_en' => 'Al Shafa', 'address_ar' => 'الشفا', 'lat' => 21.2703, 'lng' => 40.4158],
            ['city_en' => 'Abha', 'city_ar' => 'أبها', 'region_en' => 'Asir Region', 'region_ar' => 'منطقة عسير', 'address_en' => 'Al Nasb', 'address_ar' => 'النصب', 'lat' => 18.2164, 'lng' => 42.5053],
            ['city_en' => 'Tabuk', 'city_ar' => 'تبوك', 'region_en' => 'Tabuk Region', 'region_ar' => 'منطقة تبوك', 'address_en' => 'Al Morooj', 'address_ar' => 'المروج', 'lat' => 28.3838, 'lng' => 36.5662],
            ['city_en' => 'Najran', 'city_ar' => 'نجران', 'region_en' => 'Najran Region', 'region_ar' => 'منطقة نجران', 'address_en' => 'Al Fahd', 'address_ar' => 'حي الفهد', 'lat' => 17.4924, 'lng' => 44.1277],
        ];
    }
}
