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
        Schema::create('EMPLOYEES', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('branch_uuid');
            $table->uuid('job_position_uuid');
            $table->string('nik', 16);
            $table->string('name', 60);
            $table->string('email', 60)->unique();
            $table->string('whatsapp_number', 16);
            $table->string('password');
            $table->string('photo')->nullable();
            $table->json('token')->nullable();
            $table->timestamps();

            $table->foreign('branch_uuid')->references('uuid')->on('BRANCHES')->onDelete('RESTRICT');
            $table->foreign('job_position_uuid')->references('uuid')->on('JOB_POSITIONS')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('EMPLOYEES');
    }
};
