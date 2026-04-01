<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClubMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'institution_club_members';
    protected $guarded = ['id'];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function member()
    {
        if ($this->member_type === 'student') {
            return $this->belongsTo(Student::class , 'member_id');
        }
        else {
            return $this->belongsTo(Faculty::class , 'member_id');
        }
    }
}