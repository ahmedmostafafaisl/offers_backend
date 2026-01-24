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
                'monthly_price' => 49 + ($i * 5),
                'annually_price' => 499 + ($i * 50),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('plans')->insert($rows);
    }
}
