<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PVModule extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'module', 'submodule', 'rating', 'length', 'width', 'depth', 'weight'
    ];

    protected $table = 'pv_module';
}
