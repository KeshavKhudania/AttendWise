<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Schedule extends Model { 
    protected $table = 'institution_schedules'; 
    public function subject() { return $this->belongsTo(Subject::class); }
    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function faculty() { return $this->belongsTo(Faculty::class); }
    public function section() { return $this->belongsTo(Section::class); }
}
