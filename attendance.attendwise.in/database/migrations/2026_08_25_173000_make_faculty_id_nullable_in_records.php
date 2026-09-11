<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('institution_attendance_records', function (Blueprint $table) {
            $table->unsignedBigInteger('marked_by_faculty_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('institution_attendance_records', function (Blueprint $table) {
            $table->unsignedBigInteger('marked_by_faculty_id')->nullable(false)->change();
        });
    }
};
