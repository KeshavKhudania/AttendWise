<?php

namespace App\Models;

use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class InstitutionAdminGroup extends Model
{
    use HasFactory;
    use SoftDeletes;
    function institution(){
        return $this->belongsTo(Institution::class, "institution_id", "id");
    }
    protected $appends = ['encrypted_id'];

    
    public function getEncryptedIdAttribute()
    {
        return Crypt::encryptString($this->id);
    }
}
