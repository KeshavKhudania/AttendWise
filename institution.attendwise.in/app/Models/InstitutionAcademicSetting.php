<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionAcademicSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'slot_timings' => 'array',
        'working_days' => 'array',
        'holidays' => 'array',
        'extra_details' => 'array',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}