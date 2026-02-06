<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plans')->truncate();

        $now = now();

        $rows = [];
        for ($i = 1; $i <= 10; $i++) {
            $rows[] = [
                'name' => "Plan {$i}",
                'quarterly_price' => 9.99 * $i,
                'semi_annual_price' => 17.99 * $i,
                'annual_price' => 29.99 * $i,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('plans')->insert($rows);
    }
}
