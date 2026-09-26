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
        Schema::table('institution_club_members', function (Blueprint $table) {
            $table->foreignId('club_user_group_id')->nullable()->constrained('club_user_groups')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_club_members', function (Blueprint $table) {
            $table->dropForeign(['club_user_group_id']);
            $table->dropColumn('club_user_group_id');
        });
    }
};
