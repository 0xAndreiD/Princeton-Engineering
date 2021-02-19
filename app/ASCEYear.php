<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ASCEYear extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'jurisdiction_abbrev', 'asce7_in_years'
    ];

    protected $table = 'asce_year';

    public $timestamps = false;
}
