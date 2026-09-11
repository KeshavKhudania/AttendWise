<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('institution_attendance_session', function (Blueprint $table) {
            $table->string('venue')->nullable();
        });
    }

    public function down()
    {
        Schema::table('institution_attendance_session', function (Blueprint $table) {
            $table->dropColumn('venue');
        });
    }
};
