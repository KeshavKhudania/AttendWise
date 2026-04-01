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
        Schema::create('institution_classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained("institution_blocks")->onDelete('cascade');
            $table->foreignId('department_id')->constrained("institution_departments")->onDelete('cascade');
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            $table->string('name'); // e.g., "Room 101", "Lab A"
            $table->string('type')->nullable(); // e.g., "Lecture Hall", "Laboratory"
            $table->unsignedInteger('capacity')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_classrooms');
    }
};