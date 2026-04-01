<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            $table->foreignId('student_id')->constrained("institution_students")->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained("institution_schedules", "id")->onDelete('cascade');
            $table->foreignId('marked_by_faculty_id')->constrained('institution_faculties', "id")->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->string('remarks')->nullable();
            $table->timestamps();

            // A student can only have one status for a scheduled class per day
            $table->unique(['student_id', 'schedule_id', 'date'], 'attendance_unique');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_attendance_records');
    }
};