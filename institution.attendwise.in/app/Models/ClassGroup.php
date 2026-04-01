<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    use HasFactory;
    protected $table = 'institution_class_groups';
    protected $guarded = ["id"];
    function institution(){
        return $this->belongsTo(Institution::class, "institution_id", "id");
    }
    function students(){
        return $this->hasMany(Student::class, "class_group_id", "id");
    }
    function section(){
        return $this->belongsTo(Section::class, "section_id", "id");
    }
}
