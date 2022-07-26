<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PrintAddress extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'client_id', 'comany_name', 'contact_name', 'address1', 'address2', 'zip', 'city', 'state', 'telno', 'extension'
    ];

    protected $table = 'print_address';

    public $timestamps = false;
}
