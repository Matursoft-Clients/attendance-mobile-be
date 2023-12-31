<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use Uuid, HasFactory;

    protected $table = 'ANNOUNCEMENTS';

    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'thumbnail',
        'content',
    ];

    protected $primaryKey = 'uuid';
}
