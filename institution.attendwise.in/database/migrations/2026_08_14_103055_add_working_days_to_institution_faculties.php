<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institution_faculties', function (Blueprint $table) {
            // Stores e.g. ["Monday","Tuesday","Wednesday","Thursday","Friday"]
            // NULL = inherits institution academic settings working_days
            $table->json('working_days')->nullable()->after('employment_type');
        });
    }

    public function down(): void
    {
        Schema::table('institution_faculties', function (Blueprint $table) {
            $table->dropColumn('working_days');
        });
    }
};
