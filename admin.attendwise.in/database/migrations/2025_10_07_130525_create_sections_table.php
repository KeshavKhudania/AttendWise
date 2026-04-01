<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained("institution_departments")->onDelete('cascade');
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            $table->string('name'); // This is where you store "CSE-B"
            $table->string('academic_year'); // e.g., "2025-2026"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_sections');
    }
};