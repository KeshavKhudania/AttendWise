<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'institution_sections';
    protected $guarded = ["id"];
    function course()
    {
        return $this->belongsTo(Course::class , "course_id", "id");
    }
    function department()
    {
        return $this->belongsTo(Department::class , "department_id", "id");
    }
    function institution()
    {
        return $this->belongsTo(Institution::class , "institution_id", "id");
    }
    function students()
    {
        return $this->hasMany(Student::class , "section_id", "id");
    }
    function classGroups()
    {
        return $this->hasMany(ClassGroup::class , "section_id", "id");
    }
    function schedules()
    {
        return $this->hasMany(Schedule::class , "section_id", "id");
    }
    public function additionalDepartments()
    {
        return $this->belongsToMany(Department::class , 'institution_section_additional_departments', 'section_id', 'department_id');
    }
}