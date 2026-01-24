<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PendingProfileVerificationsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pending_profile_verifications')->truncate();

        $now = now();
        $users = DB::table('users')->orderBy('id')->get();
        if ($users->isEmpty()) return;

        $types = ['customer', 'provider', 'employee'];

        $rows = [];
        for ($i = 1; $i <= 10; $i++) {
            $requester = $users[($i - 1) % $users->count()];
            $target = $users[($i + 5) % $users->count()];

            $otp = (string)(100000 + $i);

            $rows[] = [
                'requester_user_id' => $requester->id,
                'target_user_id' => $target->id,
                'type' => $types[($i - 1) % 3],
                'phone' => '0599999' . str_pad((string)$i, 3, '0', STR_PAD_LEFT),
                'email' => $target->email,
                'otp_hash' => Hash::make($otp),
                'expires_at' => $now->copy()->addMinutes(10),
                'verified_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('pending_profile_verifications')->insert($rows);
    }
}
