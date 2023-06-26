<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAttendance extends Model
{
    use Uuid, HasFactory;

    protected $table = 'DAILY_ATTENDANCES';

    protected $fillable = [
        'uuid',
        'employee_uuid',
        'date',
        'presence_entry_status',
        'presence_exit_status',
        'presence_entry_address',
        'presence_entry_latitude',
        'presence_entry_longitude',
        'presence_exit_address',
        'presence_exit_latitude',
        'presence_exit_longitude',
        'reference_address',
        'reference_latitude',
        'reference_longitude',
    ];

    protected $primaryKey = 'uuid';
}
