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
        Schema::table('institution_attendance_records', function (Blueprint $table) {
            $table->index(['institution_id', 'date'], 'idx_att_inst_date');
            $table->index(['schedule_id', 'date'], 'idx_att_sched_date');
        });

        Schema::table('institution_students', function (Blueprint $table) {
            $table->index(['institution_id', 'section_id'], 'idx_stu_inst_sec');
            $table->index('roll_number', 'idx_stu_roll_number');
            $table->index('enrollment_number', 'idx_stu_enroll_number');
        });

        Schema::table('institution_faculties', function (Blueprint $table) {
            $table->index('employee_code', 'idx_fac_employee_code');
        });

        \Illuminate\Support\Facades\DB::statement('CREATE INDEX idx_stu_email_hash ON institution_students (email_hash(64))');
        \Illuminate\Support\Facades\DB::statement('CREATE INDEX idx_stu_mobile_hash ON institution_students (mobile_hash(64))');
        \Illuminate\Support\Facades\DB::statement('CREATE INDEX idx_fac_email_hash ON institution_faculties (email_hash(64))');
        \Illuminate\Support\Facades\DB::statement('CREATE INDEX idx_fac_mobile_hash ON institution_faculties (mobile_hash(64))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_attendance_records', function (Blueprint $table) {
            $table->dropIndex('idx_att_inst_date');
            $table->dropIndex('idx_att_sched_date');
        });

        Schema::table('institution_students', function (Blueprint $table) {
            $table->dropIndex('idx_stu_inst_sec');
            $table->dropIndex('idx_stu_roll_number');
            $table->dropIndex('idx_stu_enroll_number');
        });

        Schema::table('institution_faculties', function (Blueprint $table) {
            $table->dropIndex('idx_fac_employee_code');
        });

        \Illuminate\Support\Facades\DB::statement('DROP INDEX idx_stu_email_hash ON institution_students');
        \Illuminate\Support\Facades\DB::statement('DROP INDEX idx_stu_mobile_hash ON institution_students');
        \Illuminate\Support\Facades\DB::statement('DROP INDEX idx_fac_email_hash ON institution_faculties');
        \Illuminate\Support\Facades\DB::statement('DROP INDEX idx_fac_mobile_hash ON institution_faculties');
    }
};
