<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use Uuid, HasFactory;

    protected $table = 'SETTINGS';

    protected $fillable = [
        'uuid',
        'office_name',
        'office_logo',
        'presence_entry_start',
        'presence_entry_end',
        'presence_exit',
        'presence_location_latitude',
        'presence_location_longitude',
        'presence_meter_radius',
    ];

    protected $primaryKey = 'uuid';
}
