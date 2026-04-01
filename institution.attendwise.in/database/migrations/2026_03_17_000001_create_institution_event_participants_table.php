<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('institution_event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('institution_events')->onDelete('cascade');
            $table->string('participant_type'); // 'student' or 'faculty'
            $table->unsignedBigInteger('participant_id');
            $table->string('role')->nullable();
            $table->boolean('can_take_attendance')->default(0);
            $table->boolean('attendance_status')->default(0); // 1: Present, 0: Absent/Not marked
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_event_participants');
    }
};