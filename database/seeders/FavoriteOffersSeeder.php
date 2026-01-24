<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteOffersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('favorite_offers')->truncate();

        $now = now();
        $customers = DB::table('users')->where('type', 'customer')->orderBy('id')->get();
        $offers = DB::table('offers')->orderBy('id')->get();

        if ($customers->isEmpty() || $offers->isEmpty()) return;

        $rows = [];
        for ($i = 0; $i < 10; $i++) {
            $c = $customers[$i % $customers->count()];
            $o = $offers[$i % $offers->count()];
            $rows[] = [
                'user_id' => $c->id,
                'offer_id' => $o->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('favorite_offers')->insert($rows);
    }
}
