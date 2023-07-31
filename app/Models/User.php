<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'password', 'last_visit', 'language'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function uploads() {
       return $this->hasMany(Upload::class);
    }

    public function isAdmin() {
        return $this->isAdmin == 1;
    }

    public function isLogged() { //ToDo
        return true;
    }

    public function authorizeAdmin() {
        if ($this->isAdmin()) {
            return true;
        }

        abort(401);
    }

    public function authorizeLogin() {
        if ($this->isLogged()) {
            return true;
        }

        abort(401);
    }
}
