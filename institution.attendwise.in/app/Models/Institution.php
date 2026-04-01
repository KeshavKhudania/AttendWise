<?php

namespace App\Models;

use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use HasFactory;
    use SoftDeletes;
      protected $table = 'institutions';

    protected $guarded = ['id'];

    protected $casts = [
        // list sensitive fields here
        // Identity Numbers
    'pan'=>Encrypted::class,
    'gstin'=>Encrypted::class,
    'aishe_code'=>Encrypted::class,
    'aicte_approval_number'=>Encrypted::class,

    // Emails (personally identifiable)
    'authorized_signatory_email'=>Encrypted::class,
    'registrar_dean_email'=>Encrypted::class,
    'iterp_head_email'=>Encrypted::class,
    'billing_contact_email'=>Encrypted::class,

    // Contact-related identifiers
    'approved_sms_sender_id'=>Encrypted::class,
    'sms_dlt_entity_name'=>Encrypted::class,
    'data_privacy_officer_contact'=>Encrypted::class,

    // Sensitive credentials
    'api_authentication_credentials'=>Encrypted::class,

    // Potentially sensitive messaging / communication configs
    'email_domain_authentication'=>Encrypted::class,

    // Policy Docs that may contain internal confidential rules
    'attendance_policy_document'=>Encrypted::class,

    // Rosters (contain student/faculty personal info)
    'student_roster'=>Encrypted::class,
    'faculty_roster'=>Encrypted::class,

    // Program structure (institution internal info)
    'programs_and_departments'=>Encrypted::class,
        // add more as required
    ];
}
