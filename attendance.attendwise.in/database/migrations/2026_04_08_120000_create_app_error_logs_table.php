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
        Schema::create('app_error_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('device_id')->nullable();
            
            $table->text('error_message');
            $table->longText('stack_trace')->nullable();
            
            $table->string('app_version')->nullable();
            $table->json('device_info')->nullable();
            
            $table->string('api_endpoint')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_data')->nullable();
            
            $table->boolean('is_resolved')->default(false);
            $table->text('admin_note')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Foreign key to institution_students
            $table->foreign('student_id')
                  ->references('id')
                  ->on('institution_students')
                  ->onDelete('set null');
                  
            $table->index('device_id');
            $table->index('is_resolved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_error_logs');
    }
};
