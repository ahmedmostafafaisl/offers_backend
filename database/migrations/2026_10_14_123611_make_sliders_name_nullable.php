<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Raw statements: ->change() needs doctrine/dbal on Laravel 10, which this project doesn't require.
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE sliders MODIFY name VARCHAR(255) NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE sliders ALTER COLUMN name DROP NOT NULL');
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        DB::table('sliders')->whereNull('name')->update(['name' => '']);

        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE sliders MODIFY name VARCHAR(255) NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE sliders ALTER COLUMN name SET NOT NULL');
        }
    }
};
