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
        Schema::table('institution_classrooms', function (Blueprint $table) {
            if (!Schema::hasColumn('institution_classrooms', 'floor_number')) {
                $table->string('floor_number')->nullable()->after('name');
            }
            if (!Schema::hasColumn('institution_classrooms', 'has_projector')) {
                $table->boolean('has_projector')->default(false)->after('status');
            }
            if (!Schema::hasColumn('institution_classrooms', 'has_smartboard')) {
                $table->boolean('has_smartboard')->default(false)->after('has_projector');
            }
            if (!Schema::hasColumn('institution_classrooms', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('institution_classrooms', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_classrooms', function (Blueprint $table) {
            if (Schema::hasColumn('institution_classrooms', 'floor_number')) {
                $table->dropColumn('floor_number');
            }
            if (Schema::hasColumn('institution_classrooms', 'has_projector')) {
                $table->dropColumn('has_projector');
            }
            if (Schema::hasColumn('institution_classrooms', 'has_smartboard')) {
                $table->dropColumn('has_smartboard');
            }
            if (Schema::hasColumn('institution_classrooms', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('institution_classrooms', 'longitude')) {
                $table->dropColumn('longitude');
            }
        });
    }
};
