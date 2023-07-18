<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use Uuid, HasFactory;

    protected $table = 'BRANCHES';

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'presence_location_address',
        'presence_location_latitude',
        'presence_location_longitude',
    ];

    protected $primaryKey = 'uuid';
}
