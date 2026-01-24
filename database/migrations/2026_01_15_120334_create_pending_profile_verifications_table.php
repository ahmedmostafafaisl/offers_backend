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
        Schema::create('pending_profile_verifications', function (Blueprint $table) {
            $table->id();
            // اللي عامل login وبيطلب إضافة profile
            $table->unsignedBigInteger('requester_user_id');
            // صاحب الرقم اللي جبناه من users (ممكن null لو الرقم مش موجود)
            $table->unsignedBigInteger('target_user_id')->nullable();
            $table->enum('type', ['employee', 'provider', 'customer']);
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('otp_hash')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            // واحد pending لكل (requester + type + phone)
            $table->unique(['requester_user_id', 'type', 'phone'], 'ppv_req_type_phone_uq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_profile_verifications');
    }
};
