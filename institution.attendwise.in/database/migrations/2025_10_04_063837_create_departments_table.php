<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained("institutions")->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_departments');
    }
};