<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StandardFavorite extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'client_no', 'type', 'CEC', 'product_id', 'path_filename', 'pages'
    ];

    protected $table = 'standard_favorite';

    public $timestamps = false;
}
