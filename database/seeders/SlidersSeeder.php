<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Traits\SeedsImages;

class SlidersSeeder extends Seeder
{
    use SeedsImages;

    public function run(): void
    {
        DB::table('sliders')->truncate();

        $now = now();

        $names = ['Riyadh', 'Jeddah', 'Dammam', 'Makkah', 'Madinah', 'Khobar', 'Taif', 'Abha', 'Tabuk', 'Najran'];

        $rows = [];
        foreach ($names as $i => $name) {
            $rows[] = [
                'name' => "{$name} Slider",
                'image' => $this->putImage("sliders/slider_" . ($i + 1) . ".png", "Slider: {$name}"),
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('sliders')->insert($rows);
    }
}
