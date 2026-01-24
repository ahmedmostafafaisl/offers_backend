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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // FK -> users.id
            $table->unsignedBigInteger('subscription_id')->nullable(); // FK -> subscriptions.id

            $table->string('payment_type'); // geidea / tamara / tabby / cash ...
            $table->decimal('amount', 10, 2);

            $table->string('reference_id')->unique(); // auto-generated unique
            $table->string('payment_id')->nullable(); // gateway paymentIntentId / transaction id

            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');

            $table->string('phone')->nullable();

            $table->timestamps();

            $table->index(['status', 'payment_type']);
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
