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

    public function currentMember()
    {
        $student = \App\Models\Student::where('email', $this->email)
            ->orWhere('roll_number', str_replace('@student.attendwise.in', '', $this->email))
            ->first();
        
        if ($student) {
            $member = \App\Models\ClubMember::where('club_id', $this->club_id)
                ->where('member_type', 'student')
                ->where('member_id', $student->id)
                ->first();
            if ($member) return $member;
        }
        
        $faculty = \App\Models\Faculty::where('email', $this->email)->first();
        if ($faculty) {
            $member = \App\Models\ClubMember::where('club_id', $this->club_id)
                ->where('member_type', 'faculty')
                ->where('member_id', $faculty->id)
                ->first();
            if ($member) return $member;
        }
        
        return null;
    }

    public function hasPermission($permission)
    {
        $member = $this->currentMember();
        if (!$member || !$member->club_user_group_id) return false;
        
        $group = $member->userGroup;
        if (!$group) return false;
        
        $perms = $group->permissions;
        if (is_string($perms)) {
            $perms = json_decode($perms, true);
        }
        
        return is_array($perms) && in_array($permission, $perms);
    }
}
