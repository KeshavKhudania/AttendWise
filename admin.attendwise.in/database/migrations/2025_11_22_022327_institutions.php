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
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();

            // GENERAL
            $table->string('legal_name')->nullable();
            $table->string('institution_type', 100)->nullable();
            $table->year('year_of_establishment')->nullable();
            $table->string('registered_address')->nullable();

            // IDS & ACCREDITATION
            $table->string('pan', 20)->nullable();
            $table->string('gstin', 20)->nullable();
            $table->string('aishe_code', 20)->nullable();
            $table->string('aicte_approval_number', 50)->nullable();
            $table->string('naac_accreditation_grade', 10)->nullable();

            // CONTACTS
            $table->string('authorized_signatory_name', 100)->nullable();
            $table->string('authorized_signatory_email', 100)->nullable();
            $table->string('registrar_dean_name', 100)->nullable();
            $table->string('registrar_dean_email', 100)->nullable();
            $table->string('iterp_head_name', 100)->nullable();
            $table->string('iterp_head_email', 100)->nullable();

            // ACADEMICS
            $table->date('academic_calendar_start_date')->nullable();
            $table->date('academic_calendar_end_date')->nullable();
            $table->text('programs_and_departments')->nullable();
            $table->text('student_roster')->nullable();
            $table->text('faculty_roster')->nullable();

            // INTEGRATIONS
            $table->string('erp_integration_method', 100)->nullable();
            $table->string('erp_base_url', 255)->nullable();
            $table->text('api_authentication_credentials')->nullable();
            $table->string('erp_sandbox_url', 255)->nullable();

            // MESSAGING & POLICY
            $table->string('sms_dlt_entity_name', 100)->nullable();
            $table->string('approved_sms_sender_id', 20)->nullable();
            $table->string('whatsapp_business_account_status', 50)->nullable();
            $table->string('email_domain_authentication', 255)->nullable();
            $table->text('attendance_policy_document')->nullable();
            $table->string('data_privacy_officer_contact', 100)->nullable();

            // BILLING & ADMIN
            $table->string('plan_type', 50)->nullable();
            $table->string('billing_contact_name', 100)->nullable();
            $table->string('billing_contact_email', 100)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
