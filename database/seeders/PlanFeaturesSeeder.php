<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plan_features')->truncate();

        $now = now();
        $plans = DB::table('plans')->orderBy('id')->get();
        if ($plans->isEmpty()) return;

        $rows = [];
        // 3 features لكل plan => 30 record (>=10)
        foreach ($plans as $p) {
            $rows[] = ['plan_id' => $p->id, 'name' => 'Post Offers', 'description' => 'Create offers', 'created_at' => $now, 'updated_at' => $now];
            $rows[] = ['plan_id' => $p->id, 'name' => 'Analytics', 'description' => 'Basic analytics', 'created_at' => $now, 'updated_at' => $now];
            $rows[] = ['plan_id' => $p->id, 'name' => 'Support', 'description' => 'Email support', 'created_at' => $now, 'updated_at' => $now];
        }

        DB::table('plan_features')->insert($rows);
    }
}
