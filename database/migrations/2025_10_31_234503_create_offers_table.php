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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('details')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            // Add any additional fields you need for the offers table
            $table->string('location_name')->nullable();
            $table->string('location_details')->nullable();

            $table->decimal('price_before', 10, 2)->nullable();
            $table->decimal('price_after', 10, 2)->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('likes')->default(0);
            // added new
            $table->string('address_ar')->nullable();
            $table->string('city_ar')->nullable();
            $table->string('governorate_ar')->nullable();
            $table->string('country_ar')->nullable();
            $table->string('address_en')->nullable();
            $table->string('city_en')->nullable();
            $table->string('governorate_en')->nullable();
            $table->string('country_en')->nullable();
            $table->boolean('expiring_notified')->default(false);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
