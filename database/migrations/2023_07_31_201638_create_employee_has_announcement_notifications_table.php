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
        Schema::create('EMPLOYEE_HAS_ANNOUNCEMENT_NOTIFICATIONS', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('employee_uuid');
            $table->uuid('announcement_uuid');
            $table->timestamps();

            $table->foreign('employee_uuid')->references('uuid')->on('EMPLOYEES')->onDelete('CASCADE');
            $table->foreign('announcement_uuid', 'ac_uuid')->references('uuid')->on('ANNOUNCEMENTS')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('EMPLOYEE_HAS_ANNOUNCEMENT_NOTIFICATIONS');
    }
};
