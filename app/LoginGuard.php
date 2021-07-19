<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LoginGuard extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'userId', 'ipAddress', 'identity', 'created_at', 'allowed', 'blocked', 'attempts'
    ];

    protected $table = 'login_guard';

    public $timestamps = false;
}
