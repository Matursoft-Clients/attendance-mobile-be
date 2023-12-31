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
        Schema::create('CUSTOM_ATTENDANCE_LOCATIONS', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('employee_uuid');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('presence_location_address');
            $table->double('presence_location_latitude');
            $table->double('presence_location_longitude');
            $table->timestamps();

            $table->foreign('employee_uuid')->references('uuid')->on('EMPLOYEES')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CUSTOM_ATTENDANCE_LOCATIONS');
    }
};
