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
        Schema::table('institution_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->after('department_id');
            $table->integer('semester')->nullable()->after('course_id');
            $table->unsignedBigInteger('additional_department_id')->nullable()->after('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_subjects', function (Blueprint $table) {
            $table->dropColumn(['course_id', 'semester', 'additional_department_id']);
        });
    }
};
