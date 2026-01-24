<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferComplaintsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('offer_complaints')->truncate();

        $now = now();
        $customers = DB::table('users')->where('type', 'customer')->orderBy('id')->get();
        $offers = DB::table('offers')->orderBy('id')->get();
        if ($customers->isEmpty() || $offers->isEmpty()) return;

        $rows = [];
        for ($i = 0; $i < 10; $i++) {
            $rows[] = [
                'user_id' => $customers[$i % $customers->count()]->id,
                'offer_id' => $offers[$i % $offers->count()]->id,
                'subject' => "Complaint #" . ($i + 1),
                'description' => "Seeded complaint #" . ($i + 1) . " (KSA)",
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('offer_complaints')->insert($rows);
    }
}
