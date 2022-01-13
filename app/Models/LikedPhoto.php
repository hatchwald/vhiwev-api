<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikedPhoto extends Model
{
    protected $fillable = [
        'photo_id', 'user_id'
    ];
}
