<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubEvent extends Model
{
    protected $guarded = ['id'];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function eventVenue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function eventBlock()
    {
        return $this->belongsTo(Block::class, 'block_id');
    }

    public function eventClassroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
}
