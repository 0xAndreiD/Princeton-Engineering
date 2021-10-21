<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ASCERoofTypes extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'asce', 'roof_type', 'rack_crc32'
    ];

    protected $table = 'asce_roof_types';

    public $timestamps = false;
}
