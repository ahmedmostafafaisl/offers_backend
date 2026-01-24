<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subscriptions')->truncate();

        $now = now();
        $providers = DB::table('users')->where('type', 'provider')->orderBy('id')->get();
        $plans = DB::table('plans')->orderBy('id')->get();
        if ($providers->isEmpty() || $plans->isEmpty()) return;

        $rows = [];
        for ($i = 0; $i < 10; $i++) {
            $u = $providers[$i % $providers->count()];
            $p = $plans[$i % $plans->count()];

            $rows[] = [
                'user_id' => $u->id,
                'plan_id' => $p->id,
                'start_date' => now()->toDateString(),
                'expiration_date' => now()->addMonth()->toDateString(),
                'type' => 'monthly',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('subscriptions')->insert($rows);
    }
}
