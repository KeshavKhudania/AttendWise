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
        Schema::table('institution_departments', function (Blueprint $table) {
            $table->unsignedBigInteger('head_of_department_id')->nullable();
            $table->text('vision_statement')->nullable();
            $table->text('mission_statement')->nullable();
            $table->decimal('budget_allocation', 15, 2)->nullable();
        });

        Schema::table('institution_courses', function (Blueprint $table) {
            $table->string('course_type', 50)->nullable()->comment('UG, PG, Diploma');
            $table->integer('total_credits')->nullable();
        });

        Schema::table('institution_subjects', function (Blueprint $table) {
            $table->string('syllabus_url')->nullable();
            $table->decimal('passing_marks', 5, 2)->nullable();
            $table->decimal('total_marks', 5, 2)->nullable();
        });

        Schema::table('institution_classrooms', function (Blueprint $table) {
            $table->boolean('has_projector')->default(false);
            $table->boolean('has_smartboard')->default(false);
            $table->string('floor_number', 20)->nullable();
        });

        Schema::table('institution_events', function (Blueprint $table) {
            $table->unsignedBigInteger('coordinator_id')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->text('sponsors_details')->nullable();
        });

        Schema::table('institution_clubs', function (Blueprint $table) {
            $table->unsignedBigInteger('club_president_id')->nullable();
            $table->unsignedBigInteger('club_advisor_id')->nullable();
            $table->date('founding_date')->nullable();
            $table->string('meeting_schedule')->nullable();
            $table->string('logo')->nullable();
        });

        Schema::table('institution_schedules', function (Blueprint $table) {
            $table->boolean('is_online')->default(false);
            $table->string('meeting_url')->nullable();
            $table->string('materials_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_departments', function (Blueprint $table) {
            $table->dropColumn(['head_of_department_id', 'vision_statement', 'mission_statement', 'budget_allocation']);
        });

        Schema::table('institution_courses', function (Blueprint $table) {
            $table->dropColumn(['course_type', 'total_credits']);
        });

        Schema::table('institution_subjects', function (Blueprint $table) {
            $table->dropColumn(['syllabus_url', 'passing_marks', 'total_marks']);
        });

        Schema::table('institution_classrooms', function (Blueprint $table) {
            $table->dropColumn(['has_projector', 'has_smartboard', 'floor_number']);
        });

        Schema::table('institution_events', function (Blueprint $table) {
            $table->dropColumn(['coordinator_id', 'budget', 'sponsors_details']);
        });

        Schema::table('institution_clubs', function (Blueprint $table) {
            $table->dropColumn(['club_president_id', 'club_advisor_id', 'founding_date', 'meeting_schedule', 'logo']);
        });

        Schema::table('institution_schedules', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'meeting_url', 'materials_url']);
        });
    }
};
