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
        Schema::table('BRANCHES', function (Blueprint $table) {
            $table->uuid('city_uuid', 36)->nullable()->after('uuid');

            $table->foreign('city_uuid')->references('uuid')->on('CITIES')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('BRANCHES', function (Blueprint $table) {
            //
        });
    }
};
