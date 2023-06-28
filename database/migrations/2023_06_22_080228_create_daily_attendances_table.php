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
        Schema::create('DAILY_ATTENDANCES', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('employee_uuid');
            $table->dateTime('date');
            $table->enum('presence_entry_status', ['on_time', 'late', 'not_present'])->nullable();
            $table->enum('presence_exit_status', ['on_time', 'early', 'not_present'])->nullable();
            $table->text('presence_entry_address');
            $table->double('presence_entry_latitude');
            $table->double('presence_entry_longitude');
            $table->text('presence_exit_address')->nullable();
            $table->double('presence_exit_latitude')->nullable();
            $table->double('presence_exit_longitude')->nullable();
            $table->text('reference_address');
            $table->double('reference_latitude');
            $table->double('reference_longitude');
            $table->timestamps();

            $table->foreign('employee_uuid')->references('uuid')->on('EMPLOYEES')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DAILY_ATTENDANCES');
    }
};
