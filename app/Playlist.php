<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [
        'userId', 'name', 'musicURL', 'screenW', 'screenH', 'zonaGuardias'
    ];
}
