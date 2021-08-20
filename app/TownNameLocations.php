<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TownNameLocations extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'state', 'City_Town', 'Lat', 'Lng'
    ];

    protected $table = 'town_names_locations';

    public $timestamps = false;
}
