<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('institution_sections', 'working_days')) {
            Schema::table('institution_sections', function (Blueprint $table) {
            // Per-section working day override.
            // NULL = inherits institution academic settings or run-time override.
            $table->json('working_days')->nullable()->after('semester');
        });
        }
    }

    public function down(): void
    {
        Schema::table('institution_sections', function (Blueprint $table) {
            $table->dropColumn('working_days');
        });
    }
};
