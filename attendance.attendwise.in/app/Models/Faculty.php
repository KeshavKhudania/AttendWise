<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'institution_faculties';

    protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
        'designation',
        'department_id',
        'institution_id',
        'employee_code',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email' => \App\Casts\EncryptAndHash::class,
        'mobile' => \App\Casts\EncryptAndHash::class,
        'password' => \App\Casts\Encrypted::class,
        'designation' => \App\Casts\Encrypted::class,
        'email_verified_at' => 'datetime',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
