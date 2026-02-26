<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpRecord extends Model
{
    protected $table = 'ip_records';

    protected $primaryKey = 'record_id';
    public $incrementing = false;      // KTTM-001 style string IDs
    protected $keyType = 'string';

    public $timestamps = false;        // Will add timestamps later with migration

    protected $fillable = [
        'record_id',
        'ip_title',
        'category',
        'owner_inventor_summary',
        'campus',
        'status',
        'date_registered',
        'ipophl_id',
        'gdrive_link',
    ];
}
