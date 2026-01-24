<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSocialMediaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_social_media')->truncate();

        $now = now();
        $users = DB::table('users')->orderBy('id')->get();

        $rows = [];
        foreach ($users as $u) {
            $rows[] = ['user_id' => $u->id, 'platform' => 'Facebook', 'url' => 'https://facebook.com/sample', 'created_at' => $now, 'updated_at' => $now];
            $rows[] = ['user_id' => $u->id, 'platform' => 'Instagram', 'url' => 'https://instagram.com/sample', 'created_at' => $now, 'updated_at' => $now];
        }

        DB::table('user_social_media')->insert($rows);
    }
}
