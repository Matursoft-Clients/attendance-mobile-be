<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHasAnnouncementNotification extends Model
{
    use Uuid, HasFactory;

    protected $table = 'EMPLOYEE_HAS_ANNOUNCEMENT_NOTIFICATIONS';

    protected $fillable = [
        'uuid',
        'employee_uuid',
        'announcement_uuid',
    ];

    protected $primaryKey = 'uuid';
}
