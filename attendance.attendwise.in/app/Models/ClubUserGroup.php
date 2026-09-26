<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubUserGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'name',
        'description',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public const AVAILABLE_PERMISSIONS = [
        'members.view' => 'View Members',
        'members.add' => 'Add Members',
        'members.edit' => 'Edit Members',
        'members.delete' => 'Remove Members',
        'events.view' => 'View Events',
        'events.create' => 'Create Events',
        'events.edit' => 'Edit Events',
        'events.delete' => 'Delete Events',
        'attendance.take' => 'Take Attendance',
        'attendance.view' => 'View Attendance Reports',
        'groups.manage' => 'Manage User Groups',
    ];

    public function club()
    {
        return $this->belongsTo(InstitutionClub::class, 'club_id');
    }

    public function members()
    {
        return $this->hasMany(ClubMember::class, 'club_user_group_id');
    }
}
