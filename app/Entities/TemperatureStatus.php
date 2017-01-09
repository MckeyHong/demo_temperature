<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class TemperatureStatus extends Model
{
    protected $table = 'temperatureStatus';
    protected $fillable = ['value', 'status', 'time'];
}
