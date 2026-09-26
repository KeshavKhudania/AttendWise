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
        Schema::create('club_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained('institution_clubs')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'event_manager', 'member_manager'])->default('admin');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('institution_clubs', function (Blueprint $table) {
            $table->dropColumn(['email', 'password', 'remember_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_clubs', function (Blueprint $table) {
            $table->string('email')->unique()->nullable()->after('name');
            $table->string('password')->nullable()->after('email');
            $table->rememberToken()->after('password');
        });

        Schema::dropIfExists('club_managers');
    }
};
