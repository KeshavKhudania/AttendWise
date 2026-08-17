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
        Schema::table('institution_subjects', function (Blueprint $table) {
            $table->integer('max_lectures_per_day')->nullable()->after('weekly_lectures');
            $table->integer('min_lectures_per_day')->nullable()->after('max_lectures_per_day');
            $table->boolean('continuous_lectures')->default(1)->after('min_lectures_per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_subjects', function (Blueprint $table) {
            $table->dropColumn(['max_lectures_per_day', 'min_lectures_per_day', 'continuous_lectures']);
        });
    }
};
