<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferSocialMediaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('offer_social_media')->truncate();

        $now = now();
        $offers = DB::table('offers')->orderBy('id')->get();

        $rows = [];
        foreach ($offers as $offer) {
            $rows[] = ['offer_id' => $offer->id, 'platform' => 'Facebook', 'url' => 'https://facebook.com/sample', 'created_at' => $now, 'updated_at' => $now];
            $rows[] = ['offer_id' => $offer->id, 'platform' => 'Instagram', 'url' => 'https://instagram.com/sample', 'created_at' => $now, 'updated_at' => $now];
        }

        DB::table('offer_social_media')->insert($rows);
    }
}
