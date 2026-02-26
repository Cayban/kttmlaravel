<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'record_id',
        'record_title',
        'action',
        'changes',
        'user_name',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public $timestamps = true;
}
