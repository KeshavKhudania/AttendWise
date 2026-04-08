<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'institution_students';

    protected $fillable = [
        'institution_id',
        'section_id',
        'class_group_id',
        'first_name',
        'last_name',
        'name',
        'email',
        'mobile',
        'password',
        'roll_number',
        'enrollment_number',
        'course_id',
        'batch',
        'specialization',
        'address',
        'guardian_details',
        'admission_date',
        'gender',
        'academic_year',
        'date_of_birth',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'name' => \App\Casts\EncryptAndHash::class,
        'email' => \App\Casts\EncryptAndHash::class,
        'mobile' => \App\Casts\EncryptAndHash::class,
        'password' => \App\Casts\Encrypted::class,
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Enforce single device login by deleting all other sessions/tokens.
     */
    public function enforceSingleDeviceLogin(string $deviceId, string $fcmToken = null)
    {
        // 1. Delete all Sanctum tokens (forces logout of other devices at token level)
        $this->tokens()->delete();
        
        // 2. Update or Create the session record (forces logout at device/application level)
        // Since student_id is unique in student_sessions, this ensures only one active session
        StudentSession::updateOrCreate(
            ['student_id' => $this->id],
            [
                'device_id'     => $deviceId,
                'fcm_token'     => $fcmToken,
                'last_login_at' => now(),
            ]
        );
    }

    /**
     * Logout from all devices by deleting all tokens and clearing the session.
     */
    public function logoutFromAllDevices()
    {
        $this->tokens()->delete();
        $this->session()->delete();
    }

    /**
     * Get the current session for the student.
     */
    public function session()
    {
        return $this->hasOne(StudentSession::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
