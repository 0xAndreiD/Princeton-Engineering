<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Stanchion extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'module', 'submodule', 'option1', 'option2', 'crc32', 'VAC', 'watts'
    ];

    protected $table = 'stanchion';

    public $timestamps = false;
}
