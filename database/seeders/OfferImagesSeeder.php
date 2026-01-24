<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Traits\SeedsImages;

class OfferImagesSeeder extends Seeder
{
    use SeedsImages;

    public function run(): void
    {
        DB::table('offer_images')->truncate();

        $now = now();
        $offers = DB::table('offers')->orderBy('id')->get();

        $rows = [];
        foreach ($offers as $offer) {
            $rows[] = [
                'offer_id' => $offer->id,
                'image' => $this->putImage("offers/offer_{$offer->id}_1.png", "Offer #{$offer->id} Image 1"),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $rows[] = [
                'offer_id' => $offer->id,
                'image' => $this->putImage("offers/offer_{$offer->id}_2.png", "Offer #{$offer->id} Image 2"),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('offer_images')->insert($rows);
    }
}
