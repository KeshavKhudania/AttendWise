<?php

namespace App\Models;

use App\Casts\EncryptAndHash;
use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'institution_faculties';
    protected $casts = [
        "password"=>Encrypted::class,
        "designation"=>Encrypted::class,
        // "date_of_birth"=>Encrypted::class,
        "email"=>EncryptAndHash::class,
        "mobile"=>EncryptAndHash::class,
    ];
    protected $guarded = ["id"];
    function institution(){
        return $this->belongsTo(Institution::class, "institution_id", "id");
    }
    function department(){
        return $this->belongsTo(Department::class, "department_id", "id");
    }
    public function subjects()
{
    return $this->belongsToMany(
        Subject::class,
            'institution_faculty_subject',
            'faculty_id',
            'subject_id'
        )
        ->withPivot(['institution_id', 'status'])
        ->wherePivot('status', 1)
        ->whereNull('institution_faculty_subject.deleted_at');
    }
}
