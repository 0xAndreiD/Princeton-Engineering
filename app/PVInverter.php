<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PVInverter extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'module', 'submodule', 'option1', 'option2', 'crc32'
    ];

    protected $table = 'pv_inverter';

    public $timestamps = false;
}
