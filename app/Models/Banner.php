<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use Uuid, HasFactory;

    protected $table = 'BANNERS';

    protected $fillable = [
        'uuid',
        'name',
        'image',
    ];

    protected $primaryKey = 'uuid';
}
