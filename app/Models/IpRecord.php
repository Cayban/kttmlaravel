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
        'registration_number',
        'ip_title',
        'category',
        'class_of_work',
        'date_creation',
        'date_registered_deposited',
        'campus',
        'college',
        'program',
        'owner_inventor_summary',
        'gdrive_link',
        'remarks',
        'status',
        'ipophl_id',
    ];

    // ensure gdrive_link stored or returned always includes a scheme so it behaves
    // as an external URL rather than a relative route. adding this accessor means
    // model->gdrive_link and JSON serialization are both safe.
    public function getGdriveLinkAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return 'https://' . $value;
    }
}
