<?php

namespace App\Models;

use App\Casts\EncryptAndHash;
use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'institution_students';
    protected $guarded = ["id"];
    protected $casts = [
        "date_of_birth"=>Encrypted::class,
        "email"=>EncryptAndHash::class,
        "mobile"=>EncryptAndHash::class,    
        "password"=>Encrypted::class,
    ];
    function institution(){
        return $this->belongsTo(Institution::class, "institution_id", "id");
    }
    function course(){
        return $this->belongsTo(Course::class, "course_id", "id");
    }
}
