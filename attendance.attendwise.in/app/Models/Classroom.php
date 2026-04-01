<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Classroom extends Model { 
    protected $table = 'institution_classrooms'; 
    public function block() { return $this->belongsTo(Block::class); }
}
