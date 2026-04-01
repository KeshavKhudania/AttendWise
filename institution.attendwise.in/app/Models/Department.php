<?php

namespace App\Models;

use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'institution_departments';
    protected $guarded = ["id"];
    function institution()
    {
        return $this->belongsTo(Institution::class , "institution_id", "id");
    }
    public function additionalSections()
    {
        return $this->belongsToMany(Section::class , 'institution_section_additional_departments', 'department_id', 'section_id');
    }
}