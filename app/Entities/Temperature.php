<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
    protected $table = 'temperature';
    protected $fillable = ['value', 'time'];
}
