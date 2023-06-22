<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use Uuid, HasFactory;

    protected $table = 'EMPLOYEES';

    protected $fillable = [
        'uuid',
        'job_position_uuid',
        'name',
        'email',
        'password',
        'photo',
        'token',
    ];

    protected $primaryKey = 'uuid';
}
