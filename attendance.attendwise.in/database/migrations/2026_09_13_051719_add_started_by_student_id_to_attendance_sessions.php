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
            if (!Schema::hasColumn('institution_attendance_session', 'club_id')) {
                $table->unsignedBigInteger('club_id')->nullable()->after('is_geofencing');
            }
            if (!Schema::hasColumn('institution_attendance_session', 'event_id')) {
                $table->unsignedBigInteger('event_id')->nullable()->after('club_id');
            }
            if (!Schema::hasColumn('institution_attendance_session', 'started_by_student_id')) {
                $table->unsignedBigInteger('started_by_student_id')->nullable()->after('event_id');
            }
        });

        Schema::table('institution_attendance_records', function (Blueprint $table) {
            if (!Schema::hasColumn('institution_attendance_records', 'club_id')) {
                $table->unsignedBigInteger('club_id')->nullable()->after('deleted_at');
            }
            if (!Schema::hasColumn('institution_attendance_records', 'event_id')) {
                $table->unsignedBigInteger('event_id')->nullable()->after('club_id');
            }
            if (!Schema::hasColumn('institution_attendance_records', 'marked_by_student_id')) {
                $table->unsignedBigInteger('marked_by_student_id')->nullable()->after('event_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_attendance_session', function (Blueprint $table) {
            if (Schema::hasColumn('institution_attendance_session', 'club_id')) {
                $table->dropColumn('club_id');
            }
            if (Schema::hasColumn('institution_attendance_session', 'event_id')) {
                $table->dropColumn('event_id');
            }
            if (Schema::hasColumn('institution_attendance_session', 'started_by_student_id')) {
                $table->dropColumn('started_by_student_id');
            }
        });

        Schema::table('institution_attendance_records', function (Blueprint $table) {
            if (Schema::hasColumn('institution_attendance_records', 'club_id')) {
                $table->dropColumn('club_id');
            }
            if (Schema::hasColumn('institution_attendance_records', 'event_id')) {
                $table->dropColumn('event_id');
            }
            if (Schema::hasColumn('institution_attendance_records', 'marked_by_student_id')) {
                $table->dropColumn('marked_by_student_id');
            }
        });
    }
};
