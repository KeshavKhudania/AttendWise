<?php

namespace App\Models;

use App\Casts\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'institution_venues';
    protected $guarded = ['id'];

    protected $casts = [
        'latlng' => Encrypted::class ,
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}