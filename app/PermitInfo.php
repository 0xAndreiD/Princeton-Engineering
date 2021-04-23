<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PermitInfo extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'company_id', 'state', 'construction_email', 'registration', 'exp_date', 'EIN', 'FAX'
    ];

    protected $table = 'permit_info';

    public $timestamps = false;
}
