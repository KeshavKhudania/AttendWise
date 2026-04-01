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
        Schema::create('institution_class_room_types', function (Blueprint $table) {
            $table->id();

            // Multi-tenant support
            $table->unsignedBigInteger('institution_id')->nullable()
                  ->comment('Null = global type, otherwise institution specific');

            // Core fields
            $table->string('name', 100)->comment('Lecture Room, Computer Lab, etc');
            $table->string('slug', 120)->unique()->comment('lecture-room, computer-lab');

            // Optional metadata
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);

            $table->timestamps();

            // Indexes
            $table->index(['institution_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
