<?php

namespace App\Models;

use App\Casts\EncryptAndHash;
use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class AdminUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'institution_admins';
    protected $guarded = ['id'];
    protected $casts = [
        "password"=>Encrypted::class,
        "mobile"=>EncryptAndHash::class,
        "email"=>EncryptAndHash::class,
    ];
    protected $hidden = ['email', 'email_hash', "mobile", "mobile_hash", 'password'];

    // public function s    etEmailAttribute($value)
    // {
    //     $this->attributes['email'] = Crypt::encryptString($value);
    //     // $this->attributes['email_hash'] = hash('sha256', strtolower(trim($value)));
    // }

    // public function getEmailAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }
    // public function getPasswordAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }
    // public function setMobileAttribute($value)
    // {
    //     $this->attributes['mobile'] = Crypt::encryptString($value);
    //     $this->attributes['mobile_hash'] = hash('sha256', strtolower(trim($value)));
    // }

    // public function getMobileAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }
    function group(){
        return $this->belongsTo(AdminGroup::class, "admin_group_id", "id");
    }
    function institution(){
        return $this->belongsTo(Institution::class, "institution_id", "id");
    }
}
