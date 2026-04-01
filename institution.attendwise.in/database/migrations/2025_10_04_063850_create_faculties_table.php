<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_faculties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained("institution_departments")->onDelete('cascade');
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            $table->string('name');
            $table->string('mobile')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_faculties');
    }
};