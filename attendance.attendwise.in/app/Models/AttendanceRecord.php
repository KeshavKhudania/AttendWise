<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AttendanceRecord extends Model { 
    protected $table = 'institution_attendance_records'; 
    protected $fillable = ['institution_id', 'student_id', 'schedule_id', 'attendance_session_id', 'marked_by_faculty_id', 'date', 'status', 'remarks'];

    public function session() { return $this->belongsTo(AttendanceSession::class, 'attendance_session_id'); }
}
