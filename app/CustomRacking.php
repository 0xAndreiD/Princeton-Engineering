<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomRacking extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'mfr', 'model', 'style', 'angle', 'rack_weight', 'width', 'depth', 'lowest_height', 'module_spacing_EW', 'module_spacing_NS', 'url', 'favorite', 'client_no', 'crc32'
    ];

    protected $table = 'custom_solar_racking';

    public $timestamps = false;
}
