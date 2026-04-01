<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'institution_events';
    protected $guarded = ['id'];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class , 'block_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class , 'classroom_id');
    }

    public function classrooms()
    {
        return $this->belongsToMany(ClassRoom::class , 'institution_event_venues', 'event_id', 'classroom_id');
    }

    public function externalVenues()
    {
        return $this->belongsToMany(Venue::class , 'institution_event_venues', 'event_id', 'venue_id');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class , 'event_id');
    }

    public function students()
    {
        return $this->hasMany(EventParticipant::class , 'event_id')->where('participant_type', '=', 'student');
    }

    public function faculties()
    {
        return $this->hasMany(EventParticipant::class , 'event_id')->where('participant_type', '=', 'faculty');
    }
}