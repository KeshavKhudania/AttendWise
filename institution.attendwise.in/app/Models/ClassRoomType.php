<?php

namespace App\Models;

use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoomType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'institution_class_room_types';
    protected $casts = [
        // "latlng"=>Encrypted::class,
    ];
    protected $guarded = ["id"];
    function institution(){
        return $this->belongsTo(Institution::class, "institution_id", "id");
    }
}
