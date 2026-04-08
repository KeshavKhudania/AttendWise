<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppErrorLog extends Model
{
    use SoftDeletes;

    protected $table = 'app_error_logs';

    protected $fillable = [
        'student_id',
        'device_id',
        'error_message',
        'stack_trace',
        'app_version',
        'device_info',
        'api_endpoint',
        'request_payload',
        'response_data',
        'is_resolved',
        'admin_note',
    ];

    protected $casts = [
        'device_info' => 'array',
        'request_payload' => 'array',
        'response_data' => 'array',
        'is_resolved' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
