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
        Schema::create('institution_faculty_subject', function (Blueprint $table) {
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            // Foreign key for the faculties table
            $table->foreignId('faculty_id')->constrained("institution_faculties")->onDelete('cascade');

            // Foreign key for the subjects table
            $table->foreignId('subject_id')->constrained("institution_subjects")->onDelete('cascade');

            // Set the primary key to be the combination of the two foreign keys.
            // This ensures a faculty member cannot be assigned to the same subject more than once.
            $table->primary(['faculty_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_faculty_subject');
    }
};