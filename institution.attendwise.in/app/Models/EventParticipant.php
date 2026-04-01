<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    use HasFactory;

    protected $table = 'institution_event_participants';
    protected $guarded = ['id'];

    public function event()
    {
        return $this->belongsTo(Event::class , 'event_id');
    }

    public function details()
    {
        if ($this->participant_type === 'student') {
            return $this->belongsTo(Student::class , 'participant_id');
        }
        else {
            return $this->belongsTo(Faculty::class , 'participant_id');
        }
    }
}