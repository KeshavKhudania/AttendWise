<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bed extends Model
{
    protected $table = "beds";
    protected $primary_key = "id";
    use HasFactory;
    use SoftDeletes;
    function room(){
        return $this->belongsTo(Room::class, "room_id", "id");
    }
    function bed_category(){
        return $this->belongsTo(BedCategory::class, "bed_category_id");
    }
}
