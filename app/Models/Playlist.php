<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [
        'userId', 'name', 'musicURL', 'screenW', 'screenH', 'zonaGuardias'
    ];
}
