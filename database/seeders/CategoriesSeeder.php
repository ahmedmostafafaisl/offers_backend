<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Traits\SeedsImages;

class CategoriesSeeder extends Seeder
{
    use SeedsImages;

    public function run(): void
    {
        DB::table('categories')->truncate();

        $now = now();

        $names = [
            'Car Services',
            'Home Services',
            'Electronics',
            'Beauty & Wellness',
            'Restaurants',
            'Fitness',
            'Kids',
            'Health',
            'Travel',
            'Education'
        ];

        $rows = [];
        foreach ($names as $i => $name) {
            $rows[] = [
                'name' => $name,
                'image' => $this->putImage("categories/cat_" . ($i + 1) . ".png", "Category: {$name}"),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('categories')->insert($rows);
    }
}
