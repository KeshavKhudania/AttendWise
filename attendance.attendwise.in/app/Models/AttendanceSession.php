<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class AttendanceSession extends Model
{
    protected $table = 'institution_attendance_session';
    protected $fillable = [
        'uuid',
        'institution_id',
        'faculty_id',
        'schedule_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'qr_refresh_token'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function schedule() { return $this->belongsTo(Schedule::class); }
    public function faculty() { return $this->belongsTo(Faculty::class, 'faculty_id'); }
    public function records() { return $this->hasMany(AttendanceRecord::class, 'attendance_session_id'); }
}
