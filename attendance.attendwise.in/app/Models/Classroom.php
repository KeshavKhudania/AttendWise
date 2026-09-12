<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    protected $table = 'institution_classrooms';
    protected $guarded = ['id'];

    public function block()
    {
        return $this->belongsTo(Block::class, 'block_id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
