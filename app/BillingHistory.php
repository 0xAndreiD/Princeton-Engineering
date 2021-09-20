<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BillingHistory extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'companyId', 'amount', 'jobIds', 'jobCount', 'response', 'issuedAt', 'issuedFrom', 'issuedTo', 'state'
    ];

    protected $table = 'billing_history';

    public $timestamps = false;
}
