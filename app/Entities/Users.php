<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $fillable = ['email', 'password', 'name', 'active'];
    protected $hidden = ['password'];
}
