<?php

namespace App\Models;

use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'institution_classrooms';
    protected $casts = [
        "latlng" => Encrypted::class ,
    ];
    protected $guarded = ["id"];
    function institution()
    {
        return $this->belongsTo(Institution::class , "institution_id", "id");
    }
    function block()
    {
        return $this->belongsTo(Block::class , "block_id", "id");
    }
    function classroomType()
    {
        return $this->belongsTo(ClassRoomType::class , "type", "id");
    }
    function department()
    {
        return $this->belongsTo(Department::class , "department_id", "id");
    }
}