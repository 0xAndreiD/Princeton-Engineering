<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'company_name', 'legal_name', 'company_number', 'company_telno', 'company_address', 'second_address', 'company_email', 'company_website', 'offset', 'last_accessed', 'company_ip', 'longitude', 'latitude', 'max_allowable_skip', 'company_logo'
    ];

    protected $table = 'company_info';
}
