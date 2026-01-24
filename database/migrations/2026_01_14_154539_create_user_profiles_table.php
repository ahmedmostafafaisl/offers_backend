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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->unique(); // ✅ unique
            $table->unsignedBigInteger('linked_user_id')->nullable()->unique(); // ✅ unique

            $table->enum('type', ['employee', 'provider', 'customer']);

            // بيانات بروفايل عامة
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();

            // provider fields
            $table->string('whats_app_number')->nullable();
            $table->string('store_number')->nullable();
            $table->date('store_establish_date')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('commercial_registration')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
