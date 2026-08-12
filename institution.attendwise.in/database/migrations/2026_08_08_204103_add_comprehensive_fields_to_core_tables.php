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
            // Personal Details
            $table->string('blood_group', 10)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('caste_category', 50)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('mother_tongue', 50)->nullable();
            
            // Identity & Contact
            $table->string('national_id')->nullable()->comment('Aadhar, SSN, etc.');
            $table->text('permanent_address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            
            // Academic & Background
            $table->string('admission_type', 50)->nullable()->comment('Regular, Lateral, Management');
            $table->string('previous_qualification')->nullable();
            $table->string('previous_school')->nullable();
            $table->decimal('previous_marks_percentage', 5, 2)->nullable();
            
            // Facilities
            $table->boolean('is_hosteller')->default(false);
            $table->string('hostel_room_details')->nullable();
            $table->boolean('uses_transport')->default(false);
            $table->string('transport_route_details')->nullable();
            
            // Financial & Medical
            $table->string('bank_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->text('medical_history')->nullable();
            $table->string('profile_image')->nullable();
        });

        Schema::table('institution_faculties', function (Blueprint $table) {
            // Personal & Identity
            $table->string('blood_group', 10)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('national_id')->nullable();
            $table->string('pan_number')->nullable();
            
            // Contact
            $table->text('permanent_address')->nullable();
            $table->text('current_address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            
            // Professional
            $table->date('joining_date')->nullable();
            $table->date('leaving_date')->nullable();
            $table->string('employment_type', 50)->nullable()->comment('Full-time, Part-time, Contract');
            $table->string('highest_qualification')->nullable();
            $table->integer('years_of_experience')->nullable();
            
            // Financial
            $table->string('bank_account_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('basic_salary')->nullable();
            
            // Misc
            $table->string('profile_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_students', function (Blueprint $table) {
            $table->dropColumn([
                'blood_group', 'religion', 'caste_category', 'nationality', 'mother_tongue',
                'national_id', 'permanent_address', 'emergency_contact_name', 'emergency_contact_number',
                'admission_type', 'previous_qualification', 'previous_school', 'previous_marks_percentage',
                'is_hosteller', 'hostel_room_details', 'uses_transport', 'transport_route_details',
                'bank_account_no', 'bank_name', 'bank_ifsc', 'medical_history', 'profile_image'
            ]);
        });

        Schema::table('institution_faculties', function (Blueprint $table) {
            $table->dropColumn([
                'blood_group', 'nationality', 'national_id', 'pan_number',
                'permanent_address', 'current_address', 'emergency_contact_name', 'emergency_contact_number',
                'joining_date', 'leaving_date', 'employment_type', 'highest_qualification', 'years_of_experience',
                'bank_account_no', 'bank_name', 'bank_ifsc', 'basic_salary', 'profile_image'
            ]);
        });
    }
};
