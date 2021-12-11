<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SubClients extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'client_id', 'subc_client_number', 'name', 'street_1', 'street_2', 'city', 'state', 'zip', 'contact_name', 'country_code', 'telno', 'website', 'logo'
    ];

    protected $table = 'sub_clients';

    public $timestamps = false;
}
