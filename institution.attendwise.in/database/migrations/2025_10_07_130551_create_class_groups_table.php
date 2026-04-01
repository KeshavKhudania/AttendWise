<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_class_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained("institution_sections")->onDelete('cascade');
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            $table->string('name'); // This is where you store "G1", "G2", etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_class_groups');
    }
};