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
        Schema::table('institution_schedules', function (Blueprint $table) {
            $table->boolean('is_temporary')->default(0)->after('status');
            $table->date('schedule_date')->nullable()->after('is_temporary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_schedules', function (Blueprint $table) {
            $table->dropColumn(['is_temporary', 'schedule_date']);
        });
    }
};