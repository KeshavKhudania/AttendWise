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
            $table->json('timing_slot_ids')->nullable()->after('schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_attendance_session', function (Blueprint $table) {
            $table->dropColumn('timing_slot_ids');
        });
    }
};
