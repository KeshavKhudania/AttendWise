<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('institution_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            $table->foreignId('section_id')->constrained("institution_sections")->onDelete('cascade');
            $table->foreignId('subject_id')->constrained("institution_subjects")->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained("institution_faculties")->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained("institution_classrooms")->onDelete('cascade');
            $table->enum('lecture_type', ['Theory', 'Lab'])->default('Theory');
            $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_schedules');
    }
};