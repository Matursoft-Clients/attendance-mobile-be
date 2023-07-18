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
        Schema::create('BRANCHES', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->string('code');
            $table->text('presence_location_address');
            $table->double('presence_location_latitude');
            $table->double('presence_location_longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BRANCHES');
    }
};
