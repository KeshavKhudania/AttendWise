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
        Schema::table('institution_schedules', function (Blueprint $table) {
            $table->index(['institution_id', 'day_of_week', 'start_time', 'faculty_id'], 'idx_fac_busy');
            $table->index(['institution_id', 'day_of_week', 'start_time', 'section_id'], 'idx_sec_busy');
            $table->index(['institution_id', 'day_of_week', 'start_time', 'classroom_id'], 'idx_class_busy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_schedules', function (Blueprint $table) {
            $table->dropIndex('idx_fac_busy');
            $table->dropIndex('idx_sec_busy');
            $table->dropIndex('idx_class_busy');
        });
    }
};
