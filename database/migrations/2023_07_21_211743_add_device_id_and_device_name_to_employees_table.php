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
        Schema::table('EMPLOYEES', function (Blueprint $table) {
            $table->string('device_id', 50)->unique()->nullable()->after('job_position_uuid');
            $table->string('device_name', 50)->nullable()->after('device_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('EMPLOYEES', function (Blueprint $table) {
            $table->dropColumn(['device_id', 'device_name']);
        });
    }
};
