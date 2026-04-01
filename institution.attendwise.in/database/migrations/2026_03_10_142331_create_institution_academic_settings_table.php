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
        Schema::create('institution_academic_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institution_id');
            $table->integer('slots_per_day')->default(8);
            $table->integer('faculty_lecture_limit')->default(6);
            $table->integer('theory_slot_limit')->default(1);
            $table->integer('lab_slot_limit')->default(2);
            $table->json('slot_timings')->nullable();
            $table->json('working_days')->nullable();
            $table->json('holidays')->nullable();
            $table->json('extra_details')->nullable();
            $table->timestamps();

            $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_academic_settings');
    }
};