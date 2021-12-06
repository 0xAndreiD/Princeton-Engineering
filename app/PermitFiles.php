<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PermitFiles extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'state', 'description', 'type', 'filename', 'tabname', 'formtype'
    ];

    protected $table = 'permit_files';

    public $timestamps = false;
}
