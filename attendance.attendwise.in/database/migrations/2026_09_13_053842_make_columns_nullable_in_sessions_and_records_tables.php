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
        Schema::table('institution_attendance_session', function (Blueprint $table) {
            $table->unsignedBigInteger('faculty_id')->nullable()->change();
            $table->unsignedBigInteger('schedule_id')->nullable()->change();
        });

        Schema::table('institution_attendance_records', function (Blueprint $table) {
            $table->unsignedBigInteger('schedule_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_attendance_session', function (Blueprint $table) {
            $table->unsignedBigInteger('faculty_id')->nullable(false)->change();
            $table->unsignedBigInteger('schedule_id')->nullable(false)->change();
        });

        Schema::table('institution_attendance_records', function (Blueprint $table) {
            $table->unsignedBigInteger('schedule_id')->nullable(false)->change();
        });
    }
};
