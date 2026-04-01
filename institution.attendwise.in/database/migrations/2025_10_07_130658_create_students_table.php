<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('institution_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained("institution_sections")->onDelete('cascade');
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            $table->foreignId('class_group_id')->nullable()->constrained('institution_class_groups')->onDelete('set null');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->nullable()->unique();
            $table->string('roll_number')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_students');
    }
};