<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('institution_event_venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('institution_events')->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained('institution_classrooms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_event_venues');
    }
};