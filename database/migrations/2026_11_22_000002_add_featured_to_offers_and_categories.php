<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['offers', 'categories'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('is_featured')->default(false)->index();
                $table->unsignedInteger('featured_order')->default(0);
            });
        }
    }

    public function down(): void
    {
        foreach (['offers', 'categories'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropIndex($tableName . '_is_featured_index');
                $table->dropColumn(['is_featured', 'featured_order']);
            });
        }
    }
};
