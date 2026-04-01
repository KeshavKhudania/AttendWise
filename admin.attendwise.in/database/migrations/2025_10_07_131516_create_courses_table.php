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
        Schema::create('institution_courses', function (Blueprint $table) {
            $table->id();

            // Each course is offered by a department
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            $table->foreignId('department_id')->constrained("institution_departments")->onDelete('cascade');
            $table->string('name'); // e.g., "Bachelor of Technology in Computer Science"
            $table->string('code')->unique(); // e.g., "BTECH-CSE"
            $table->enum('level', ['Undergraduate', 'Postgraduate', 'Diploma', 'Certificate']);
            $table->unsignedTinyInteger('duration_years'); // e.g., 4
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_courses');
    }
};