<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use Uuid, HasFactory;

    protected $table = 'CITIES';

    protected $fillable = [
        'uuid',
        'code',
        'name',
    ];

    protected $primaryKey = 'uuid';
}
