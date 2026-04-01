<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('institution_subjects', function (Blueprint $table) {
            if (Schema::hasColumn('institution_subjects', 'weekly_hours')) {
                $table->renameColumn('weekly_hours', 'weekly_lectures');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_subjects', function (Blueprint $table) {
            if (Schema::hasColumn('institution_subjects', 'weekly_lectures')) {
                $table->renameColumn('weekly_lectures', 'weekly_hours');
            }
        });
    }
};