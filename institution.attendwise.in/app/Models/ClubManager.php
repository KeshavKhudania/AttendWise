<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClubManager extends Authenticatable
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['password', 'remember_token'];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
