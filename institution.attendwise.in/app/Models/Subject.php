<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'institution_subjects';
    protected $casts = [
        'continuous_lectures'  => 'boolean',
        'max_lectures_per_day' => 'integer',
        'min_lectures_per_day' => 'integer',
        'weekly_lectures'      => 'integer',
    ];
    protected $guarded = ["id"];
    function institution(){
        return $this->belongsTo(Institution::class, "institution_id", "id");
    }
    function department(){
        return $this->belongsTo(Department::class, "department_id", "id");
    }
    function additionalDepartment(){
        return $this->belongsTo(Department::class, "additional_department_id", "id");
    }
    function course(){
        return $this->belongsTo(Course::class, "course_id", "id");
    }
    function classroom(){
        return $this->belongsTo(ClassRoomType::class, "classroom_type", "id");
    }
    public function faculties()
{
    return $this->belongsToMany(
        Faculty::class,
        'institution_faculty_subject',
        'subject_id',
        'faculty_id'
    )
    ->withPivot(['institution_id', 'status'])
    ->wherePivot('status', 1)
    ->whereNull('institution_faculty_subject.deleted_at');
}

}
