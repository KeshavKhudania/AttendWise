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
        Schema::table('institution_students', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropUnique(['roll_number']); 
        });

        Schema::table('institution_students', function (Blueprint $table) {
            $table->text('email')->change();
            $table->text('mobile')->nullable()->change();
            $table->text('roll_number')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_students', function (Blueprint $table) {
            $table->string('email', 191)->change();
            $table->string('mobile', 191)->nullable()->change();
            $table->string('roll_number', 191)->change();
        });

        Schema::table('institution_students', function (Blueprint $table) {
            $table->unique(['email']);
            $table->unique(['roll_number']);
        });
    }
};
