<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomAttendanceLocation extends Model
{
    use Uuid, HasFactory;

    protected $table = 'CUSTOM_ATTENDANCE_LOCATIONS';

    protected $fillable = [
        'uuid',
        'employee_uuid',
        'start_date',
        'end_date',
        'presence_location_latitude',
        'presence_location_longitude',
    ];

    protected $primaryKey = 'uuid';
}
