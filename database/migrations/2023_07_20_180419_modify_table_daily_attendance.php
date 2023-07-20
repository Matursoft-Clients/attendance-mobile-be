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
        Schema::table('DAILY_ATTENDANCES', function (Blueprint $table) {
            $table->enum('presence_entry_status', ['on_time', 'late', 'not_present', 'not_valid'])->nullable()->change();
            $table->double('presence_entry_latitude')->nullable()->change();
            $table->double('presence_entry_longitude')->nullable()->change();
            $table->text('reference_address')->nullable()->change();
            $table->double('reference_latitude')->nullable()->change();
            $table->double('reference_longitude')->nullable()->change();
            $table->dropColumn(['presence_entry_address', 'presence_exit_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DAILY_ATTENDANCES', function (Blueprint $table) {
            //
        });
    }
};
