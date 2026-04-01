<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SemesterSubject extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'institution_semester_subjects';
    protected $guarded = ["id"];
    protected $casts = [
        "subjects" => "array"
    ];

    function department(){
        return $this->belongsTo(Department::class, "department_id", "id");
    }
    function course(){
        return $this->belongsTo(Course::class, "course_id", "id");
    }
    // function subjectsList(){
    //     $list = unserialize($this->subjects);
    // }
}
