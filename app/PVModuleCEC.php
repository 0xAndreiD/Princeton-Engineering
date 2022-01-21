<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PVModuleCEC extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'mfr', 'model', 'rating', 'length', 'width', 'depth', 'weight', 'crc32', 'VAC', 'watts'
    ];

    protected $table = 'pv_module_cec';

    public $timestamps = false;
}
