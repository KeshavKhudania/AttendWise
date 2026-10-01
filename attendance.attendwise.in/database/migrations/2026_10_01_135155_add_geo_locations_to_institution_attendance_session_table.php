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
        if (!\Illuminate\Support\Facades\Schema::hasColumn('institution_attendance_session', 'geo_locations')) {
            Schema::table('institution_attendance_session', function (Blueprint $table) {
                $table->json('geo_locations')->nullable()->after('is_geofencing');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_attendance_session', function (Blueprint $table) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('institution_attendance_session', 'geo_locations')) {
                $table->dropColumn('geo_locations');
            }
        });
    }
};
