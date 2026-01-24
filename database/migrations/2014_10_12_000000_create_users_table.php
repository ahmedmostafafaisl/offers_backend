<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('active_profile_id')->nullable();
            $table->enum('type', ['employee', 'provider', 'customer']);
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('otp')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('photo')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('whats_app_number')->nullable();
            $table->string('store_number')->nullable();
            $table->date('store_establish_date')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('commercial_registration')->nullable();
            $table->rememberToken();
            // added new
            $table->string('address_ar')->nullable();
            $table->string('city_ar')->nullable();
            $table->string('governorate_ar')->nullable();
            $table->string('country_ar')->nullable();
            $table->string('address_en')->nullable();
            $table->string('city_en')->nullable();
            $table->string('governorate_en')->nullable();
            $table->string('country_en')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            // Add any additional fields you need for the offers table
            $table->string('location_name_ar')->nullable();
            $table->string('location_details_ar')->nullable();
            $table->string('location_name_en')->nullable();
            $table->string('location_details_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
