<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    use HasFactory;
    protected $table = 'institution_import_logs';
    protected $guarded = ["id"];
    protected $casts = ['errors' => 'array', ];

}
