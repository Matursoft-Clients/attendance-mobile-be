<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    use Uuid, HasFactory;

    protected $table = 'JOB_POSITIONS';

    protected $fillable = [
        'uuid',
        'name',
        'code',
    ];

    protected $primaryKey = 'uuid';
}
