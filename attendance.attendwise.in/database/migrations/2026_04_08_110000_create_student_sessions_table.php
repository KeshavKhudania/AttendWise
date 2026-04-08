<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->unique(); // Unique ensures only one session per student
            $table->string('device_id')->index();
            $table->text('fcm_token')->nullable();
            $table->string('platform')->nullable(); // ios or android
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();

            // Foreign key to institution_students
            $table->foreign('student_id')
                  ->references('id')
                  ->on('institution_students')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_sessions');
    }
};
