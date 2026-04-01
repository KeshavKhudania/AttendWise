<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institution_section_additional_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('institution_sections')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('institution_departments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_section_additional_departments');
    }
};