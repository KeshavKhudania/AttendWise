<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionNotification extends Model
{
    protected $fillable = [
        'institution_id',
        'title',
        'message',
        'data',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];
}
