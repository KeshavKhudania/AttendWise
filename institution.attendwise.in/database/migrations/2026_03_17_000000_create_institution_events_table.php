<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('institution_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained('institutions')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('event_type'); // Workshop, Seminar, etc.
            $table->foreignId('block_id')->nullable()->constrained('institution_blocks')->onDelete('set null');
            $table->foreignId('classroom_id')->nullable()->constrained('institution_classrooms')->onDelete('set null');
            $table->string('venue_details')->nullable();
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->boolean('is_open')->default(0); // 1: Everyone is eligible, 0: Invitation/Recruitment only
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_events');
    }
};