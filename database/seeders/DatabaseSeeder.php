<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,

            PlansSeeder::class,
            PlanFeaturesSeeder::class,
            SubscriptionsSeeder::class,

            CategoriesSeeder::class,

            OffersSeeder::class,
            OfferImagesSeeder::class,
            OfferSocialMediaSeeder::class,

            UserSocialMediaSeeder::class,

            FavoriteOffersSeeder::class,
            OfferReportsSeeder::class,
            OfferComplaintsSeeder::class,

            SlidersSeeder::class,

            UserProfilesSeeder::class,
            PendingProfileVerificationsSeeder::class,
            RolesAndPermissionsSeeder::class,
            PaymentsSeeder::class,


        ]);
    }
}
