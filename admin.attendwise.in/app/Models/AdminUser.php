<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    function group(){
        return $this->belongsTo(AdminGroup::class, "group_id", "id");
    }
}
