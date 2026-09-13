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
        DB::statement("ALTER TABLE institution_attendance_records MODIFY status ENUM('present', 'absent', 'late', 'excused', 'cancelled', 'club_pending')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE institution_attendance_records MODIFY status ENUM('present', 'absent', 'late', 'excused', 'cancelled')");
    }
};
