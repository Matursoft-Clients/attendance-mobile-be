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
        Schema::create('SETTINGS', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('office_name', 60);
            $table->string('office_logo')->nullable();
            $table->string('presence_entry_start');
            $table->string('presence_entry_end');
            $table->string('presence_exit');
            $table->text('presence_location_address');
            $table->double('presence_location_latitude');
            $table->double('presence_location_longitude');
            $table->integer('presence_meter_radius');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SETTINGS');
    }
};
